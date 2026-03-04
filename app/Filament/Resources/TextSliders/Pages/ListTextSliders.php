<?php

namespace App\Filament\Resources\TextSliders\Pages;

use App\Filament\Resources\TextSliders\TextSliderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTextSliders extends ListRecords
{
    protected static string $resource = TextSliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
