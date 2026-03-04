<?php

namespace App\Filament\Resources\Menus;

use App\Filament\Resources\Menus\Pages\CreateMenu;
use App\Filament\Resources\Menus\Pages\EditMenu;
use App\Filament\Resources\Menus\Pages\ListMenus;
use App\Models\Menu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    // BÜTÜN TİP (TYPE) HATALARINI KÖKTEN ÇÖZEN GARANTİLİ METODLAR:
    public static function getNavigationLabel(): string
    {
        return 'Menüler';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'SİTE YÖNETİMİ';
    }

    public static function getPluralLabel(): string
    {
        return 'Menüler';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Menü Bilgileri')
                    ->schema([
                        Select::make('parent_id')
                            ->label('Üst Menü Seçiniz')
                            ->options(Menu::all()->pluck('title', 'id'))
                            ->searchable()
                            ->placeholder('Ana Menü (Eğer üst menü yoksa boş bırakın)'),

                        TextInput::make('title')
                            ->label('Menü Adı')
                            ->required(),

                        TextInput::make('url')
                            ->label('Link (URL)')
                            ->placeholder('Örn: /hakkimizda')
                            ->required(),

                        TextInput::make('order')
                            ->label('Sıra')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_active')
                            ->label('Aktif/Pasif')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('parent.title')
                    ->label('Üst Menü')
                    ->placeholder('-'),

                TextColumn::make('title')
                    ->label('Menü Adı')
                    ->searchable(),

                TextColumn::make('order')
                    ->label('Sıra')
                    ->sortable(),

                // ToggleColumn sayesinde direkt tablo üzerinden aktif/pasif yapabileceksin
                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                TextColumn::make('created_at')
                    ->label('Ekleme Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('order', 'asc') // Otomatik olarak Sıra'ya göre dizer
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}