<?php

namespace App\Filament\App\Resources\Post\Pages;

use App\Filament\App\Resources\Post\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
