<?php

namespace App\Filament\App\Resources\Maps; 

use App\Filament\App\Resources\Maps\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\Map;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class MapResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'maps';
    protected static ?string $model = Map::class;

    // Menü için şık bir harita/konum ikonu
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    public static function getNavigationLabel(): string
    {
        return 'Harita Yönetimi';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Diğer Özellikler';
    }

    protected static ?int $navigationSort = 160;

    public static function getPluralLabel(): string
    {
        return 'Haritalar';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Harita Detayları')
                    ->description('Google Maps üzerinden aldığınız iframe kodunu buraya yapıştırın.')
                    ->schema([
                        Select::make('page')
                            ->label('Bağlı Olduğu Sayfa')
                            ->options([
                                'iletisim' => 'İletişim Sayfası',
                                'footer' => 'Footer (Alt Bilgi)',
                            ])
                            ->required()
                            ->native(false)
                            ->helperText('İletişim: Max 6 adet | Footer: Max 1 adet'),

                        TextInput::make('title')
                            ->label('Harita Başlığı / Ofis Adı')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('iframe_code')
                            ->label('Google Maps Iframe Kodu')
                            ->required()
                            ->rows(5)
                            ->rules([
                                'regex:/^\s*<iframe\b[^>]*>.*<\/iframe>\s*$/is',
                            ])
                            ->validationMessages([
                                'regex' => 'Lütfen yalnızca geçerli bir iframe kodu girin.',
                            ])
                            ->placeholder('<iframe src="https://www.google.com/maps/embed?..." ...></iframe>')
                            ->helperText('Sadece iframe kodu kabul edilir.')
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('page')
                    ->label('Sayfa')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('title')
                    ->label('Harita Adı')
                    ->searchable(),

                ToggleColumn::make('is_active')
                    ->label('Durum'),

                TextColumn::make('created_at')
                    ->label('Ekleme Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('page')
                    ->label('Sayfaya Göre Filtrele')
                    ->options(fn () => Map::pluck('page', 'page')->toArray()),
            ])
            ->actions([
                EditAction::make()->label('Düzenle')->button(),
                DeleteAction::make()->label('Sil')->button(),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->label('Seçilileri Sil'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaps::route('/'),
            'create' => Pages\CreateMap::route('/create'),
            'edit' => Pages\EditMap::route('/{record}/edit'),
        ];
    }
}