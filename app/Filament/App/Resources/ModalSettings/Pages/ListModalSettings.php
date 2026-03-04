<?php

namespace App\Filament\App\Resources\ModalSettings\Pages;

use App\Filament\App\Resources\ModalSettings\ModalSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListModalSettings extends ListRecords
{
    protected static string $resource = ModalSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
