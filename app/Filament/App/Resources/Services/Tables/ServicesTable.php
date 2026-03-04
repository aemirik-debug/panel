<?php

namespace App\Filament\App\Resources\Services\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('order') 
            ->defaultSort('order', 'asc')
            ->columns([
                ImageColumn::make('image')
                    ->label('Görsel')
                    ->disk('public')
                    ->circular()
                    ->width(50),

                TextColumn::make('title')
                    ->label('Servis Başlığı')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('URL Uzantısı'),

                ToggleColumn::make('is_active')
                    ->label('Durum'),

                TextColumn::make('order')
                    ->label('Sıra')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                // Grup (BulkActionGroup) kullanmadık, direkt o kırmızı geniş buton çıksın diye:
                DeleteBulkAction::make()
                    ->label('Seçilenleri Sil'),
            ]);
    }
}