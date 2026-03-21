<?php

namespace App\Filament\App\Resources\SupportTickets\Pages;

use App\Filament\App\Resources\SupportTickets\SupportTicketResource;
use App\Models\SupportTicket;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class EditSupportTicket extends EditRecord
{
    protected static string $resource = SupportTicketResource::class;

    protected ?string $pendingReplyMessage = null;

    public bool $replySentNotice = false;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)
                ->columnSpanFull()
                ->schema([
                    Grid::make(1)
                        ->columnSpan(1)
                        ->schema([
                            Section::make('Talep Detayı')
                                ->schema([
                                    Placeholder::make('category')
                                        ->label('Kategori')
                                        ->content(fn (SupportTicket $record) => match ($record->category) {
                                            'blog' => 'Blog',
                                            'products' => 'Ürünler',
                                            'services' => 'Hizmetler',
                                            'categories' => 'Kategoriler',
                                            'gallery' => 'Galeri',
                                            'slider' => 'Slider',
                                            'menu' => 'Menüler',
                                            'settings' => 'Ayarlar',
                                            default => 'Diğer',
                                        }),

                                    Placeholder::make('status')
                                        ->label('Durum')
                                        ->content(fn (SupportTicket $record) => SupportTicket::getStatusLabel($record->status)),

                                    Placeholder::make('created_at')
                                        ->label('Oluşturulma')
                                        ->content(fn (SupportTicket $record) => $record->created_at?->format('d.m.Y H:i')),

                                    Placeholder::make('screenshot_preview')
                                        ->label('Ekran Görüntüsü')
                                        ->content(fn (SupportTicket $record) => $record->screenshot
                                            ? new \Illuminate\Support\HtmlString(
                                                '<img src="' . asset('storage/' . $record->screenshot) . '" class="max-w-full rounded-xl border border-gray-200" style="max-height:320px" />'
                                            )
                                            : 'Eklenmemiş'
                                        )
                                        ->columnSpanFull()
                                        ->visible(fn (SupportTicket $record) => (bool) $record->screenshot),
                                ])
                                ->columns(1),

                            Section::make('Talep Yanıtı')
                                ->description('Buraya yazdığınız mesaj süper admin panelindeki talep detayında görünecektir.')
                                ->schema([
                                    Placeholder::make('reply_inline_notice')
                                        ->label(false)
                                        ->content(fn () => $this->replySentNotice
                                            ? new HtmlString('<div class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800">Mesajınız gönderildi ve sağdaki yazışma geçmişine eklendi.</div>')
                                            : new HtmlString('<div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">Kaydet butonuna bastığınızda mesajınız anında yazışma geçmişine eklenir.</div>')
                                        )
                                        ->columnSpanFull(),

                                    Textarea::make('tenant_reply_message')
                                        ->label('Yanıtınız')
                                        ->rows(6)
                                        ->placeholder('Destek talebiyle ilgili ek bilgi ya da yanıtınızı yazın.')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Section::make('Yazışma Geçmişi')
                        ->columnSpan(2)
                        ->description('Müşteri ve süper admin arasındaki tüm yazışmalar burada sıralanır.')
                        ->schema([
                            Placeholder::make('conversation_history')
                                ->label(false)
                                ->content(fn (SupportTicket $record) => $record->renderConversationHtml())
                                ->columnSpanFull(),
                        ]),
                ]),
        ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['tenant_reply_message'] = '';
        $this->replySentNotice = false;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->replySentNotice = false;
        $this->pendingReplyMessage = trim((string) ($data['tenant_reply_message'] ?? ''));

        unset($data['tenant_reply_message']);

        return $data;
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        if ($this->record->hasUnreadReplyForTenant()) {
            $this->record->update(['customer_notified' => true]);
        }
    }

    protected function afterSave(): void
    {
        if (filled($this->pendingReplyMessage)) {
            $this->record->appendReply(
                SupportTicket::ACTOR_TENANT_ADMIN,
                $this->pendingReplyMessage,
                auth()->user()?->name ?? $this->record->user_name,
            );

            Notification::make()
                ->title('Yanıtınız gönderildi')
                ->body('Mesajınız süper admin paneline iletildi.')
                ->success()
                ->send();

            $this->replySentNotice = true;
            $this->form->fill(['tenant_reply_message' => '']);
            $this->pendingReplyMessage = null;
        }
    }
}
