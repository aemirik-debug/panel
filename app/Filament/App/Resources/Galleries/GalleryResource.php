<?php

namespace App\Filament\App\Resources\Galleries;

use App\Filament\App\Resources\Galleries\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\Album;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class GalleryResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'galleries';

    protected static ?string $model = Album::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?int $navigationSort = 20;

    public static function getNavigationLabel(): string
    {
        return 'Fotoğraf Galerisi';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Görsel & Medya';
    }

    public static function getPluralLabel(): string
    {
        return 'Fotoğraf Galerisi';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Albüm Bilgileri')
                    ->schema([
                        TextInput::make('title')
                            ->label('Albüm Adı')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Albüm Açıklaması')
                            ->rows(3)
                            ->placeholder('Bu albüm ne ile ilgili? Kısa bir açıklama yazabilirsiniz.')
                            ->columnSpanFull(),

                        Select::make('show_on')
                            ->label('Gösterim Alanları')
                            ->multiple()
                            ->options([
                                'home' => 'Ana Sayfa',
                                'about' => 'Hakkımızda',
                                'services' => 'Hizmetler',
                            ])
                            ->helperText('Seçim yapmazsanız albüm sadece Foto Galeri sayfasında görünür.')
                            ->searchable(),

                        TextInput::make('order')
                            ->label('Sıra')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_active')
                            ->label('Yayında mı?')
                            ->default(true),
                    ])
                    ->columns(1),

                Section::make('Albüm Fotoğrafları')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Fotoğraflar')
                            ->multiple()
                            ->live()
                            ->reorderable()
                            ->maxFiles(25)
                            ->maxSize(4096)
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->helperText('Sadece JPG/PNG kabul edilir. Maksimum 25 fotoğraf, dosya başı 4 MB.')
                            ->disk('public')
                            ->directory('galleries/albums')
                            ->visibility('public')
                            ->fetchFileInformation(false)
                            ->image()
                            ->imagePreviewHeight('300')
                            ->panelLayout('grid')
                            ->columns(4)
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('1600')
                            ->imageResizeTargetHeight('1600')
                            ->required(fn (string $operation): bool => $operation === 'create'),

                        Placeholder::make('images_count_info')
                            ->label('Yuklenen Foto Sayisi')
                            ->content(fn (Get $get): string => count($get('images') ?? []) . ' / 25'),

                        Select::make('cover_image')
                            ->label('Kapak Gorseli')
                            ->options(function (Get $get): array {
                                $images = $get('images') ?? [];

                                return collect($images)
                                    ->filter()
                                    ->mapWithKeys(fn (string $path): array => [$path => basename($path)])
                                    ->all();
                            })
                            ->helperText('Bos birakirsaniz ilk yuklenen gorsel kapak olarak kullanilir.')
                            ->searchable()
                            ->native(false),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Kapak')
                    ->state(fn (Album $record): ?string => $record->cover_image),

                TextColumn::make('title')
                    ->label('Albüm')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(60)
                    ->placeholder('-'),

                TextColumn::make('show_on')
                    ->label('Gösterim Alanı')
                    ->formatStateUsing(function ($state): string {
                        $labels = [
                            'home' => 'Ana Sayfa',
                            'about' => 'Hakkımızda',
                            'services' => 'Hizmetler',
                        ];

                        $selected = collect($state ?? [])->map(fn ($item) => $labels[$item] ?? $item);

                        return $selected->isEmpty() ? 'Sadece Foto Galeri' : $selected->implode(', ');
                    }),

                TextColumn::make('images_count')
                    ->label('Fotoğraf')
                    ->state(fn (Album $record): int => count($record->images ?? [])),

                TextColumn::make('order')
                    ->label('Sıra')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),
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
