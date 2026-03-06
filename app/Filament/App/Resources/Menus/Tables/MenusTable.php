<?php

namespace App\Filament\App\Resources\Menus\Tables;

use App\Models\Menu;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

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

                Tables\Columns\TextColumn::make('menu_type')
                    ->label('🧭 Sayfa Tipi')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'home' => 'Ana Sayfa',
                        'about' => 'Hakkımızda',
                        'services' => 'Hizmetler',
                        'references' => 'Referanslar',
                        'portfolio' => 'Projeler',
                        'blog' => 'Blog',
                        'contact' => 'İletişim',
                        'custom_page' => 'Özel Sayfa',
                        'custom_url', null, '' => 'Özel URL',
                        default => $state,
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'home', 'about', 'services', 'references', 'portfolio', 'blog', 'contact' => 'success',
                        'custom_page' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('page.title')
                    ->label('📄 Bağlantılı Sayfa')
                    ->placeholder('—')
                    ->searchable(),

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
                EditAction::make(),
                ViewAction::make('view')
                    ->label('Görüntüle'),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                DeleteBulkAction::make(),
                ]),
            ]);
    }
}
