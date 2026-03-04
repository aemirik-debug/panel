<?php

namespace App\Filament\App\Resources\SidebarLinks\Pages;

use App\Filament\App\Resources\SidebarLinks\SidebarLinkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSidebarLink extends EditRecord
{
    protected static string $resource = SidebarLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
