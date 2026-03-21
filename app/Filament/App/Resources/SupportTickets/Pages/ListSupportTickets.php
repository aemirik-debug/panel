<?php

namespace App\Filament\App\Resources\SupportTickets\Pages;

use App\Filament\App\Resources\SupportTickets\SupportTicketResource;
use App\Models\SupportTicket;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSupportTickets extends ListRecords
{
    protected static string $resource = SupportTicketResource::class;

    public function mount(): void
    {
        parent::mount();

        $count = $this->getUnreadReplyCount();

        if ($count > 0) {
            Notification::make()
                ->title('Süper admin yanıtı var')
                ->body($count === 1 ? '1 destek talebiniz için yeni yanıt geldi.' : $count . ' destek talebiniz için yeni yanıt geldi.')
                ->success()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('markAllRead')
                ->label('Yanıtları Okundu İşaretle')
                ->icon('heroicon-o-check')
                ->color('gray')
                ->visible(fn () => $this->getUnreadReplyCount() > 0)
                ->action(function (): void {
                    SupportTicket::query()
                        ->where('tenant_id', tenant()?->id)
                        ->where('customer_notified', false)
                        ->whereNotNull('admin_reply')
                        ->update(['customer_notified' => true]);
                }),
            CreateAction::make()->label('Yeni Destek Talebi Ekle'),
        ];
    }

    public function getSubheading(): ?string
    {
        $count = $this->getUnreadReplyCount();

        if ($count > 0) {
            return $count . ' talebiniz için yeni destek yanıtı var.';
        }

        return 'Taleplerinizi buradan görüntüleyebilir, yeni talep oluşturabilirsiniz.';
    }

    protected function getUnreadReplyCount(): int
    {
        return SupportTicket::query()
            ->where('tenant_id', tenant()?->id)
            ->where('customer_notified', false)
            ->whereNotNull('admin_reply')
            ->count();
    }
}
