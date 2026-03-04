<?php

namespace App\Filament\App\Resources\QuizResults\Pages;

use App\Filament\App\Resources\QuizResults\QuizResultResource;
use Filament\Resources\Pages\ListRecords;

class ListQuizResults extends ListRecords
{
    protected static string $resource = QuizResultResource::class;

    protected function getHeaderActions(): array
    {
        // Panelden yeni sınav sonucu eklenemeyeceği için boş bırakıyoruz
        return [];
    }
}