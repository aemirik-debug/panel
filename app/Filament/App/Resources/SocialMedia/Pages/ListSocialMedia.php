<?php

namespace App\Filament\App\Resources\SocialMedia\Pages;

use App\Filament\App\Resources\SocialMedia\SocialMediaResource;
use App\Models\SocialMedia;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListSocialMedia extends ListRecords
{
    protected static string $resource = SocialMediaResource::class;

    public function mount(): void
    {
        parent::mount();

        $record = SocialMedia::query()->first();

        if ($record) {
            $this->redirect(static::getResource()::getUrl('edit', ['record' => $record]));

            return;
        }

        $this->redirect(static::getResource()::getUrl('create'));
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
