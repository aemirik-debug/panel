<?php

namespace App\Filament\App\Resources\SidebarLinks\Pages;

use App\Filament\App\Resources\SidebarLinks\SidebarLinkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSidebarLinks extends ListRecords
{
    protected static string $resource = SidebarLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    public function getSubheading(): ?string
    {
        return 'Bu alanda ilgili kayitlari yonetebilirsiniz.';
    }
}

