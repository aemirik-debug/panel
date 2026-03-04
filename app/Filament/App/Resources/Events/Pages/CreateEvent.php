<?php

namespace App\Filament\App\Resources\Events\Pages;

use App\Filament\App\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;
}
