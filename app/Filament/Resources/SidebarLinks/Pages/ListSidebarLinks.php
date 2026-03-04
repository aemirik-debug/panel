<?php

namespace App\Filament\Resources\SidebarLinks\Pages;

use App\Filament\Resources\SidebarLinks\SidebarLinkResource;
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
}
