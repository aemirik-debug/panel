<?php

namespace App\Filament\App\Resources\Post\Pages;

use App\Filament\App\Resources\Post\PostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPost extends ListRecords
{
    protected static string $resource = PostResource::class;

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

