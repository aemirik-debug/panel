<?php

namespace App\Filament\Resources\ModalSettings\Pages;

use App\Filament\Resources\ModalSettings\ModalSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditModalSetting extends EditRecord
{
    protected static string $resource = ModalSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
