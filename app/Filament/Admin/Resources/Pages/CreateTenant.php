<?php

namespace App\Filament\Admin\Resources\Pages;

use App\Filament\Admin\Resources\TenantResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function afterCreate(): void
    {
        $tenant = $this->record;
        $tenantId = (string) $tenant->id;
        $domain = optional($tenant->domains()->first())->domain ?? ($tenantId . '.test');

        Notification::make()
            ->title('Musteri panel girisi olusturuldu')
            ->success()
            ->persistent()
            ->body("Panel: http://{$domain}/yonetim\nE-posta: admin@{$tenantId}.com\nSifre: 12345678\n\nIlk giriste sifreyi degistirmeniz onerilir.")
            ->send();
    }
}
