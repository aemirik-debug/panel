<?php

namespace App\Filament\App\Resources\TextSliders\Pages;

use App\Filament\App\Resources\TextSliders\TextSliderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTextSlider extends EditRecord
{
    protected static string $resource = TextSliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
