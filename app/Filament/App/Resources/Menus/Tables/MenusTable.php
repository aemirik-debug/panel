<?php

namespace App\Filament\App\Resources\Menus\Tables;

use App\Models\Menu;
use Filament\Tables;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('📌 Menü Başlığı')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state, Menu $record) {
                        if ($record->parent_id) {
                            return '└─ ' . $state;
                        }
                        return '📌 ' . $state;
                    }),

                Tables\Columns\TextColumn::make('url')
                    ->label('🔗 Bağlantı')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(fn(Menu $record): string => $record->url),

                Tables\Columns\TextColumn::make('parent.title')
                    ->label('👨‍👧 Üst Menü')
                    ->placeholder('Ana')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('📊 Sıra')
                    ->sortable()
                    ->alignment('center'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('✅ Aktif')
                    ->boolean()
                    ->sortable()
                    ->alignment('center'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('📅 Ekleme Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Durum')
                    ->queries(
                        true: fn($query) => $query->where('is_active', true),
                        false: fn($query) => $query->where('is_active', false),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\View::make('view')
                    ->label('Görüntüle'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
