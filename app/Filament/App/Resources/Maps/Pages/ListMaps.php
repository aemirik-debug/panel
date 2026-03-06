<?php

namespace App\Filament\App\Resources\Maps\Pages;

use App\Filament\App\Resources\Maps\MapResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMaps extends ListRecords
{
    protected static string $resource = MapResource::class;

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

