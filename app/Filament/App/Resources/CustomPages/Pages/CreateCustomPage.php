<?php

namespace App\Filament\App\Resources\CustomPages\Pages;

use App\Filament\App\Resources\CustomPages\CustomPageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomPage extends CreateRecord
{
    protected static string $resource = CustomPageResource::class;
}
