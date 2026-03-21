<?php

namespace App\Filament\Admin\Resources\SupportTicketCentralResource\Pages;

use App\Filament\Admin\Resources\SupportTicketCentralResource;
use App\Models\SupportTicket;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class EditSupportTicketCentral extends EditRecord
{
    protected static string $resource = SupportTicketCentralResource::class;

    protected ?string $pendingReplyMessage = null;

    public bool $replySentNotice = false;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        // Sayfayı açınca okundu olarak işaretle
        if (! $this->record->is_read) {
            $this->record->update(['is_read' => true]);
        }
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
                                    \Filament\Forms\Components\Placeholder::make('tenant_domain')
                                        ->label('Müşteri')
                                        ->content(fn (SupportTicket $record) => $record->tenant_domain),

                                    \Filament\Forms\Components\Placeholder::make('user_info')
                                        ->label('Gönderen')
                                        ->content(fn (SupportTicket $record) => "{$record->user_name} ({$record->user_email})"),

                                    \Filament\Forms\Components\Placeholder::make('category')
                                        ->label('Kategori')
                                        ->content(fn (SupportTicket $record) => match($record->category) {
                                            'blog'       => 'Blog',
                                            'products'   => 'Ürünler',
                                            'services'   => 'Hizmetler',
                                            'categories' => 'Kategoriler',
                                            'gallery'    => 'Galeri',
                                            'slider'     => 'Slider',
                                            'menu'       => 'Menüler',
                                            'settings'   => 'Ayarlar',
                                            default      => 'Diğer',
                                        }),

                                    \Filament\Forms\Components\Placeholder::make('created_at')
                                        ->label('Gönderilme Tarihi')
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

                            Section::make('Durum Yönetimi')
                                ->description('Yanıt yazıp "Kaydet" butonuna tıkladığınızda müşteri destek sayfasında bildirim görür.')
                                ->schema([
                                    Placeholder::make('reply_inline_notice')
                                        ->label(false)
                                        ->content(fn () => $this->replySentNotice
                                            ? new HtmlString('<div class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800">Yanıtınız gönderildi ve yazışma geçmişine eklendi.</div>')
                                            : new HtmlString('<div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">Kaydet butonuna bastığınızda yanıtınız anında yazışma geçmişine eklenir.</div>')
                                        )
                                        ->columnSpanFull(),

                                    Textarea::make('super_admin_reply_message')
                                        ->label('Süper Admin Yanıtı')
                                        ->helperText('Bu yanıt müşterinin destek sayfasında gösterilecektir.')
                                        ->rows(5)
                                        ->placeholder('Talebe ilişkin yanıtınızı yazın.')
                                        ->columnSpanFull(),

                                    Select::make('status')
                                        ->label('Talep Durumu')
                                        ->options(SupportTicket::formStatusOptions())
                                        ->native(false)
                                        ->required(),
                                ]),
                        ]),

                    Section::make('Yazışma Geçmişi')
                        ->columnSpan(2)
                        ->description('Bu alanda sadece yazışmalar listelenir.')
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
        $data['status'] = SupportTicket::statusToFormKey($data['status'] ?? null);
        $data['super_admin_reply_message'] = '';
        $this->replySentNotice = false;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->replySentNotice = false;
        $this->pendingReplyMessage = trim((string) ($data['super_admin_reply_message'] ?? ''));
        $data['status'] = SupportTicket::formKeyToStatus($data['status'] ?? null);

        unset($data['super_admin_reply_message']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Listeye Dön')
                ->icon('heroicon-o-arrow-left')
                ->url(SupportTicketCentralResource::getUrl('index'))
                ->color('gray'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Destek talebi güncellendi.';
    }

    protected function getRedirectUrl(): string
    {
        return SupportTicketCentralResource::getUrl('index');
    }

    protected function afterSave(): void
    {
        if (filled($this->pendingReplyMessage)) {
            $this->record->appendReply(
                SupportTicket::ACTOR_SUPER_ADMIN,
                $this->pendingReplyMessage,
                auth()->user()?->name ?? 'Süper Admin',
            );

            Notification::make()
                ->title('Yanıt gönderildi')
                ->body('Yanıt admin paneline bildirildi ve konuşma geçmişine eklendi.')
                ->success()
                ->send();

            $this->replySentNotice = true;
            $this->form->fill(['super_admin_reply_message' => '']);
            $this->pendingReplyMessage = null;
        }
    }
}
