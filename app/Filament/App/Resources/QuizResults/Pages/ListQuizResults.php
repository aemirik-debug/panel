<?php

namespace App\Filament\App\Resources\QuizResults\Pages;

use App\Filament\App\Resources\QuizResults\QuizResultResource;
use Filament\Resources\Pages\ListRecords;

class ListQuizResults extends ListRecords
{
    protected static string $resource = QuizResultResource::class;

    protected function getHeaderActions(): array
    {
        // Panelden yeni sÄ±nav sonucu eklenemeyeceÄŸi iÃ§in boÅŸ bÄ±rakÄ±yoruz
        return [];
    }
    public function getSubheading(): ?string
    {
        return 'Bu alanda ilgili kayitlari yonetebilirsiniz.';
    }
}

