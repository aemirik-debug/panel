<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

class SupportTicket extends Model
{
    use HasFactory;

    public const FORM_STATUS_NEW = 'new';
    public const FORM_STATUS_SEEN = 'seen';
    public const FORM_STATUS_ANSWERED = 'answered';
    public const FORM_STATUS_CLOSED = 'closed';

    public const STATUS_NEW = 'yeni';
    public const STATUS_SEEN = 'görüldü';
    public const STATUS_ANSWERED = 'yanıtlandı';
    public const STATUS_CLOSED = 'kapalı';

    public const ACTOR_TENANT_ADMIN = 'tenant_admin';
    public const ACTOR_SUPER_ADMIN = 'super_admin';

    // Merkezi veritabanı bağlantısı - admin tüm tenantların taleplerini görebilsin
    protected $connection = 'sqlite';

    protected $table = 'support_tickets_central';

    protected $fillable = [
        'tenant_id',
        'tenant_domain',
        'user_name',
        'user_email',
        'category',
        'title',
        'message',
        'screenshot',
        'status',
        'is_read',
        'admin_reply',
        'conversation_history',
        'last_reply_author',
        'customer_notified',
    ];

    protected $casts = [
        'conversation_history' => 'array',
        'is_read'           => 'boolean',
        'customer_notified' => 'boolean',
    ];

    public static function statusOptions(): array
    {
        return [
            self::STATUS_NEW => 'Yeni',
            self::STATUS_SEEN => 'İnceleniyor',
            self::STATUS_ANSWERED => 'Yanıtlandı',
            self::STATUS_CLOSED => 'Kapandı',
        ];
    }

    public static function formStatusOptions(): array
    {
        return [
            self::FORM_STATUS_NEW => 'Yeni',
            self::FORM_STATUS_SEEN => 'İnceleniyor',
            self::FORM_STATUS_ANSWERED => 'Yanıtlandı',
            self::FORM_STATUS_CLOSED => 'Kapandı',
        ];
    }

    public static function normalizeStatus(string|null $status): string
    {
        return match ($status) {
            'inceleniyor' => self::STATUS_SEEN,
            'cevaplandi' => self::STATUS_ANSWERED,
            'kapandi' => self::STATUS_CLOSED,
            null, '' => self::STATUS_NEW,
            default => $status,
        };
    }

    public static function statusToFormKey(string|null $status): string
    {
        return match (static::normalizeStatus($status)) {
            self::STATUS_NEW => self::FORM_STATUS_NEW,
            self::STATUS_SEEN => self::FORM_STATUS_SEEN,
            self::STATUS_ANSWERED => self::FORM_STATUS_ANSWERED,
            self::STATUS_CLOSED => self::FORM_STATUS_CLOSED,
            default => self::FORM_STATUS_NEW,
        };
    }

    public static function formKeyToStatus(string|null $formStatus): string
    {
        return match ($formStatus) {
            self::FORM_STATUS_SEEN => self::STATUS_SEEN,
            self::FORM_STATUS_ANSWERED => self::STATUS_ANSWERED,
            self::FORM_STATUS_CLOSED => self::STATUS_CLOSED,
            default => self::STATUS_NEW,
        };
    }

    public static function statusColors(): array
    {
        return [
            self::STATUS_NEW => 'danger',
            self::STATUS_SEEN => 'warning',
            self::STATUS_ANSWERED => 'success',
            self::STATUS_CLOSED => 'gray',
        ];
    }

    public static function statusBadgeClasses(): array
    {
        return [
            self::STATUS_NEW => 'bg-red-100 text-red-700',
            self::STATUS_SEEN => 'bg-amber-100 text-amber-700',
            self::STATUS_ANSWERED => 'bg-emerald-100 text-emerald-700',
            self::STATUS_CLOSED => 'bg-gray-100 text-gray-500',
        ];
    }

    public static function getStatusLabel(string $status): string
    {
        $status = static::normalizeStatus($status);

        return static::statusOptions()[$status] ?? $status;
    }

    public static function getStatusColor(string $status): string
    {
        $status = static::normalizeStatus($status);

        return static::statusColors()[$status] ?? 'gray';
    }

    public static function getStatusBadgeClass(string $status): string
    {
        $status = static::normalizeStatus($status);

        return static::statusBadgeClasses()[$status] ?? 'bg-gray-100 text-gray-500';
    }

    public static function getActorLabel(string $actorType): string
    {
        return match ($actorType) {
            self::ACTOR_SUPER_ADMIN => 'Süper Admin',
            default => 'Müşteri',
        };
    }

    public function hasUnreadReplyForTenant(): bool
    {
        return ! $this->customer_notified && filled($this->admin_reply);
    }

    public function conversationEntries(): array
    {
        $entries = [
            [
                'author_type' => self::ACTOR_TENANT_ADMIN,
                'author_name' => $this->user_name ?: static::getActorLabel(self::ACTOR_TENANT_ADMIN),
                'message' => (string) $this->message,
                'created_at' => $this->created_at?->toIso8601String(),
                'is_initial' => true,
                'is_html' => true,
            ],
        ];

        foreach (($this->conversation_history ?? []) as $entry) {
            $message = trim((string) ($entry['message'] ?? ''));

            if ($message === '') {
                continue;
            }

            $authorType = (string) ($entry['author_type'] ?? self::ACTOR_SUPER_ADMIN);

            $entries[] = [
                'author_type' => $authorType,
                'author_name' => (string) ($entry['author_name'] ?? static::getActorLabel($authorType)),
                'message' => $message,
                'created_at' => $entry['created_at'] ?? null,
                'is_initial' => false,
                'is_html' => false,
            ];
        }

        return $entries;
    }

    public function renderConversationHtml(): HtmlString
    {
        $viewerActorType = $this->getViewerActorType();

        $cards = array_map(function (array $entry): string {
            $isOwnMessage = $entry['author_type'] === $this->getViewerActorType();
            $isInitial = (bool) ($entry['is_initial'] ?? false);
            $rowClasses = $isOwnMessage ? 'justify-content-end' : 'justify-content-start';
            $bubbleClasses = $isOwnMessage ? 'bg-danger text-white' : 'bg-success text-white';
            $title = $isInitial
                ? 'Müşteri Talebi'
                : ($entry['author_type'] === self::ACTOR_SUPER_ADMIN ? 'Süper Admin Yanıtı' : 'Müşteri Yanıtı');
            $timestamp = static::formatConversationTimestamp($entry['created_at'] ?? null);
            $message = $entry['is_html']
                ? (string) $entry['message']
                : nl2br(e((string) $entry['message']));

            return '<div class="d-flex ' . $rowClasses . '">'
                . '<div class="rounded-4 px-3 py-2 shadow-sm ' . $bubbleClasses . '" style="max-width: 75%;">'
                . '<div class="d-flex align-items-center gap-2 mb-2 small fw-semibold">'
                . '<span>' . e($title) . '</span>'
                . '<span aria-hidden="true">&bull;</span>'
                . '<span>' . e($timestamp) . '</span>'
                . '</div>'
                . '<div style="line-height: 1.5;">' . $message . '</div>'
                . '</div>';
        }, $this->conversationEntries());

        return new HtmlString('<div class="d-flex flex-column gap-3 p-2">' . implode('', $cards) . '</div>');
    }

    protected function getViewerActorType(): string
    {
        $path = request()->path();

        if (str_starts_with($path, 'admin')) {
            return self::ACTOR_SUPER_ADMIN;
        }

        return self::ACTOR_TENANT_ADMIN;
    }

    public function appendReply(string $actorType, string $message, ?string $authorName = null): void
    {
        $message = trim($message);

        if ($message === '') {
            return;
        }

        $history = $this->conversation_history ?? [];
        $history[] = [
            'author_type' => $actorType,
            'author_name' => $authorName ?: static::getActorLabel($actorType),
            'message' => $message,
            'created_at' => now()->toIso8601String(),
        ];

        $attributes = [
            'conversation_history' => $history,
            'last_reply_author' => $actorType,
        ];

        if ($actorType === self::ACTOR_SUPER_ADMIN) {
            $attributes['admin_reply'] = $message;
            $attributes['customer_notified'] = false;
        }

        if ($actorType === self::ACTOR_TENANT_ADMIN) {
            $attributes['is_read'] = false;
        }

        $this->forceFill($attributes)->saveQuietly();
        $this->refresh();
    }

    protected static function formatConversationTimestamp(string|null $timestamp): string
    {
        if (blank($timestamp)) {
            return 'Zaman bilgisi yok';
        }

        try {
            return Carbon::parse($timestamp)->format('d.m.Y H:i');
        } catch (\Throwable) {
            return (string) $timestamp;
        }
    }

    /**
     * Kategori ve mesajdan otomatik başlık üret
     */
    public static function generateTitle(string $category, string $message): string
    {
        $categoryLabels = [
            'blog'       => 'Blog',
            'products'   => 'Ürünler',
            'services'   => 'Hizmetler',
            'categories' => 'Kategoriler',
            'gallery'    => 'Galeri',
            'slider'     => 'Slider',
            'menu'       => 'Menüler',
            'settings'   => 'Ayarlar',
            'other'      => 'Diğer',
        ];

        $label = $categoryLabels[$category] ?? 'Destek';

        // HTML taglarını temizle ve ilk 80 karakteri al
        $clean = strip_tags($message);
        $clean = html_entity_decode($clean, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $clean = preg_replace('/\s+/', ' ', trim($clean));
        $excerpt = mb_substr($clean, 0, 60);

        return "[$label] " . ($excerpt ?: 'Yeni talep');
    }

    /**
     * Okunmamış talep sayısı
     */
    public static function unreadCount(): int
    {
        return static::where('is_read', false)->count();
    }
}
