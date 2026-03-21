<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SupportTicketCentralResource\Pages;
use App\Models\SupportTicket;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SupportTicketCentralResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Destek Talepleri';

    protected static ?string $pluralLabel = 'Destek Talepleri';

    protected static ?int $navigationSort = 50;

    public static function getNavigationBadge(): ?string
    {
        $count = SupportTicket::where('is_read', false)->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant_domain')
                    ->label('Müşteri')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('user_name')
                    ->label('Gönderen')
                    ->description(fn (SupportTicket $r) => $r->user_email)
                    ->searchable(),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'blog'       => 'Blog',
                        'products'   => 'Ürünler',
                        'services'   => 'Hizmetler',
                        'categories' => 'Kategoriler',
                        'gallery'    => 'Galeri',
                        'slider'     => 'Slider',
                        'menu'       => 'Menüler',
                        'settings'   => 'Ayarlar',
                        default      => 'Diğer',
                    })
                    ->color('info'),

                TextColumn::make('title')
                    ->label('Konu')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => SupportTicket::getStatusLabel($state))
                    ->color(fn (string $state) => SupportTicket::getStatusColor($state)),

                IconColumn::make('is_read')
                    ->label('Okundu')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('success')
                    ->falseColor('warning'),

                TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options(SupportTicket::statusOptions()),

                SelectFilter::make('is_read')
                    ->label('Okunma Durumu')
                    ->options([
                        '0' => 'Okunmamış',
                        '1' => 'Okunmuş',
                    ]),
            ])
            ->actions([
                EditAction::make()
                    ->label('Görüntüle'),
            ])
            ->recordUrl(fn (SupportTicket $record) => static::getUrl('edit', ['record' => $record]));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportTicketCentrals::route('/'),
            'edit'  => Pages\EditSupportTicketCentral::route('/{record}/edit'),
        ];
    }
}
