<?php

namespace App\Filament\App\Resources\CustomPages\Pages;

use App\Filament\App\Resources\CustomPages\CustomPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomPages extends ListRecords
{
    protected static string $resource = CustomPageResource::class;

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

