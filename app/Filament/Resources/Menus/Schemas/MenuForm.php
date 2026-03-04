<?php

namespace App\Filament\Resources\Menus\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            TextInput::make('title')
                ->label('Menü Başlığı')
                ->required(),
            TextInput::make('url')
                ->label('URL / Link')
                ->required()
                ->placeholder('/hakkimizda'),
            TextInput::make('order')
                ->label('Sıralama')
                ->numeric()
                ->default(0),
            ]);
    }
}
