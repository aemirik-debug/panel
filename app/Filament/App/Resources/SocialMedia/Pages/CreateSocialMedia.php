<?php

namespace App\Filament\App\Resources\SocialMedia\Pages;

use App\Filament\App\Resources\SocialMedia\SocialMediaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSocialMedia extends CreateRecord
{
    protected static string $resource = SocialMediaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
