<?php

namespace App\Filament\App\Resources\Comments\Pages;

use App\Filament\App\Resources\Comments\CommentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateComment extends CreateRecord
{
    protected static string $resource = CommentResource::class;
}
