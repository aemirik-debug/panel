<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Setting extends Model
{
    use TenantConnection;

    public const HOME_SECTION_DEFINITIONS = [
        'split_slider' => 'Bolunmus Slider',
        'services' => 'Hizmetler',
        'cta' => 'Harekete Gec',
        'references' => 'Referanslar',
        'gallery' => 'Foto Galeri Akisi',
        'posts' => 'Son Yazilar',
    ];

    // Formdan gelecek tüm alanları buraya yazıyoruz
    protected $fillable = [
        'site_name',
        'phone',
        'email',
        'address',
        'logo',
        'favicon',
        'footer_text',
        'meta_title',        // Senin istediğin "Site Başlığı (Title)"
        'meta_description',  // Senin istediğin "Site Tanıtım Cümlesi (Desc)"
        'hero_title',
        'hero_subtitle',
        'hero_button_text',
        'hero_button_link',
        'hero_background',
        'slider_display_mode',
        'services_section_title',
        'services_description',
        'cta_title',
        'cta_description',
        'primary_color',
        'secondary_color',
        // --- Bizim eklediğimiz yeni alanlar ---
        'site_keywords',     // Senin istediğin "Anahtar Kelimeler"
        'google_analytics',  // Senin istediğin "Google Analytics Kodu"
        'contact_notification_email',
        'send_contact_notifications',
        'references_section_title',
        'references_section_description',
        'portfolio_section_title',
        'portfolio_section_description',
        'show_home_gallery_button',
        'home_sections',
        // --- Mail Sunucu Ayarları ---
        'use_custom_mail_settings',
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];

    protected $casts = [
        'send_contact_notifications' => 'boolean',
        'show_home_gallery_button' => 'boolean',
        'home_sections' => 'array',
        'use_custom_mail_settings' => 'boolean',
        'mail_password' => 'encrypted',
        'mail_port' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $setting): void {
            $setting->home_sections = self::normalizeHomeSections($setting->home_sections);
        });
    }

    public static function getDefaultHomeSections(): array
    {
        return collect(self::HOME_SECTION_DEFINITIONS)
            ->map(fn (string $label, string $key): array => [
                'key' => $key,
                'label' => $label,
                'is_visible' => true,
            ])
            ->values()
            ->all();
    }

    public static function normalizeHomeSections(?array $sections): array
    {
        $sections = collect($sections ?? [])
            ->filter(fn ($row): bool => is_array($row) && filled($row['key'] ?? null))
            ->map(fn (array $row): array => [
                'key' => (string) ($row['key'] ?? ''),
                'is_visible' => (bool) ($row['is_visible'] ?? true),
            ]);

        $keyToVisible = $sections->pluck('is_visible', 'key');

        $orderedKnownKeys = $sections
            ->pluck('key')
            ->filter(fn (string $key): bool => array_key_exists($key, self::HOME_SECTION_DEFINITIONS))
            ->unique()
            ->values();

        $missingKeys = collect(array_keys(self::HOME_SECTION_DEFINITIONS))
            ->diff($orderedKnownKeys)
            ->values();

        return $orderedKnownKeys
            ->merge($missingKeys)
            ->map(fn (string $key): array => [
                'key' => $key,
                'label' => self::HOME_SECTION_DEFINITIONS[$key],
                'is_visible' => (bool) ($keyToVisible->get($key) ?? true),
            ])
            ->values()
            ->all();
    }
}