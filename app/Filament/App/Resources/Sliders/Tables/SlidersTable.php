<?php

namespace App\Filament\App\Resources\Sliders\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class SlidersTable
{
    public static function configure(Table $table): Table
    {
        return $table
			->reorderable('sort')
			->defaultSort('sort', 'asc') 
            ->columns([
                ImageColumn::make('image')
                    ->label('Görsel')
                    ->disk('public')
                    ->circular() 
                    ->width(50),
                
                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable(),

                TextColumn::make('slider_model')
                    ->label('Model')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'split_layout' => 'Model 2 - Split',
                        default => 'Model 1 - Full',
                    })
                    ->badge(),
                
                ToggleColumn::make('is_active')
                    ->label('Aktif/Pasif'),
            ])
            ->filters([
                //
            ])
            // Menü tarafındaki "doğru" dediğin yapı tam olarak budur:
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
        // Group'u (BulkActionGroup::make) tamamen siliyoruz!
        // Doğrudan silme eylemini koyuyoruz:
        \Filament\Actions\DeleteBulkAction::make()
            ->label('Delete selected'), // İstersen buraya istediğin yazıyı yazabilirsin
    ]);
    }
}