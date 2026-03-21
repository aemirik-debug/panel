<?php

namespace App\Filament\App\Resources\SupportTickets\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupportTicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Destek Talebi')
                    ->description('Sorununuzu detaylıca yazın. Yanıtlar bu kayıt üzerinden size iletilecektir.')
                    ->schema([
                        Select::make('category')
                            ->label('Sorun Kategorisi')
                            ->options([
                                'blog' => 'Blog / Haberler',
                                'products' => 'Ürünler',
                                'services' => 'Hizmetler',
                                'categories' => 'Kategoriler',
                                'gallery' => 'Galeri',
                                'slider' => 'Slider',
                                'menu' => 'Menüler',
                                'settings' => 'Ayarlar',
                                'other' => 'Diğer',
                            ])
                            ->required()
                            ->disabledOn('edit')
                            ->native(false),

                        RichEditor::make('message')
                            ->label('Sorun Açıklaması')
                            ->required()
                            ->disabledOn('edit')
                            ->disableToolbarButtons(['attachFiles'])
                            ->columnSpanFull(),

                        FileUpload::make('screenshot')
                            ->label('Ekran Görüntüsü (İsteğe Bağlı)')
                            ->image()
                            ->directory('support-tickets')
                            ->disk('public')
                            ->maxSize(5120)
                            ->disabledOn('edit')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Destek Yanıtı')
                    ->schema([
                        Textarea::make('admin_reply')
                            ->label('Destek Ekibi Yanıtı')
                            ->disabled()
                            ->rows(6)
                            ->placeholder('Henüz yanıt verilmedi.')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => filled($record?->admin_reply)),
            ]);
    }
}
