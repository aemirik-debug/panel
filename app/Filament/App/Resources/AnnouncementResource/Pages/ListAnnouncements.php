<?php

namespace App\Filament\App\Resources\AnnouncementResource\Pages;

use App\Filament\App\Resources\AnnouncementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

class ListAnnouncements extends ListRecords
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [];
        
        if (AnnouncementResource::canCreateMore()) {
            $actions[] = Actions\CreateAction::make();
        } else {
            $limit = AnnouncementResource::getAnnouncementLimit();
            $actions[] = Actions\CreateAction::make()
                ->disabled()
                ->tooltip("Paketiniz maksimum {$limit} duyuru oluşturmaya izin verir. Limitinize ulaştınız.");
        }
        
        return $actions;
    }

    public function getTitle(): string
    {
        $limit = AnnouncementResource::getAnnouncementLimit();
        $count = $this->getFilteredTableQuery()->count();
        $packageName = match(tenant()->package) {
            'baslangic' => 'Başlangıç',
            'profesyonel' => 'Profesyonel',
            'kurumsal' => 'Kurumsal',
            default => 'Bilinmeyen'
        };
        
        return "Duyurular ({$count}/{$limit}) - {$packageName} Paketi";
    }
}

