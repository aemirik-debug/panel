<?php

namespace App\Filament\Resources\TextSliders\Pages;

use App\Filament\Resources\TextSliders\TextSliderResource;
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
