<?php

namespace App\Filament\App\Resources\Sliders\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema; 

class SliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                FileUpload::make('image')
                    ->label('Slider Görseli')
                    ->image()
                    ->directory('sliders')
					->disk('public')
					->visibility('public')
                    ->required(),
                
                TextInput::make('title')
                    ->label('Ana Başlık'),
                
                TextInput::make('subtitle')
                    ->label('Alt Başlık'),

                TextInput::make('button_text')
                    ->label('Buton Yazısı'),

                TextInput::make('button_url')
                    ->label('Buton Linki'),

                Toggle::make('is_active')
                    ->label('Yayında mı?')
                    ->default(true),
            ]);
    }
}