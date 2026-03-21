<?php

namespace App\Filament\App\Resources\SupportTickets\Tables;

use App\Models\SupportTicket;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class SupportTicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Konu')
                    ->searchable()
                    ->sortable()
                    ->limit(60),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'blog' => 'Blog',
                        'products' => 'Ürünler',
                        'services' => 'Hizmetler',
                        'categories' => 'Kategoriler',
                        'gallery' => 'Galeri',
                        'slider' => 'Slider',
                        'menu' => 'Menüler',
                        'settings' => 'Ayarlar',
                        default => 'Diğer',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => SupportTicket::getStatusLabel($state))
                    ->color(fn (string $state) => SupportTicket::getStatusColor($state)),

                Tables\Columns\TextColumn::make('admin_reply')
                    ->label('Bildirim')
                    ->badge()
                    ->state(fn (SupportTicket $record) => $record->hasUnreadReplyForTenant() ? 'Yeni Yanıt' : '')
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                EditAction::make()->label('Detay'),
            ]);
    }
}
