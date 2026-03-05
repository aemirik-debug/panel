<?php

namespace App\Filament\App\Resources\Galleries;

use App\Filament\App\Resources\Galleries\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\Gallery;
use App\Models\Menu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class GalleryResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'galleries';
    
    protected static ?string $model = Gallery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    public static function getNavigationLabel(): string
    {
        return 'Fotoğraf Galerisi';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Modüller';
    }

    public static function getPluralLabel(): string
    {
        return 'Fotoğraf Galerisi';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Galeri Bilgileri')
                    ->schema([
                        Select::make('menu_id')
                            ->label('Bağlı Olduğu Sayfa')
                            ->options(Menu::all()->pluck('title', 'id'))
                            ->searchable()
                            ->placeholder('Sayfa seçiniz (İsteğe bağlı)'),

                        TextInput::make('title')
                            ->label('Görsel Başlığı / Açıklama')
                            ->placeholder('İsteğe bağlı...'),
                    ])->columns(2),

                Section::make('Galeri Görseli')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Fotoğraf')
                            ->image()
                            ->directory('galleries')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('order')
                            ->label('Sıra')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_active')
                            ->label('Yayında mı?')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Görsel')
                    ->circular(),

                TextColumn::make('menu.title')
                    ->label('Bağlı Olduğu Sayfa')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Bağımsız'),

                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('order')
                    ->label('Sıra')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                TextColumn::make('created_at')
                    ->label('Yüklenme Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('order', 'asc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}