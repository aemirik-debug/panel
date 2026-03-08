<?php

namespace App\Filament\App\Resources\AnnouncementResource\Pages;

use App\Filament\App\Resources\AnnouncementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Auth\Access\AuthorizationException;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;

    /**
     * Paket limitine göre duyuru oluşturma izni kontrol et
     */
    public function mount(): void
    {
        if (!AnnouncementResource::canCreateMore()) {
            $limit = AnnouncementResource::getAnnouncementLimit();
            $package = tenant()->package;
            
            throw new AuthorizationException(
                "Paketiniz ({$package}) en fazla {$limit} duyuru oluşturmaya izin verir. " .
                "Mevcut limitinize ulaştınız. Daha fazla duyuru başmak için lütfen paket yükseltin."
            );
        }

        parent::mount();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
