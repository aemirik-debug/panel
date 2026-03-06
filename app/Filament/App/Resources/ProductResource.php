<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ProductResource\Pages;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    public static function getNavigationLabel(): string
    {
        return 'Ürünler';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'İçerik Yönetimi';
    }

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('📦 Ürün Bilgileri')
                    ->schema([
                        TextInput::make('name')
                            ->label('Ürün Adı')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('URL (Slug)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Otomatik olarak ürün adından oluşturulur.')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Select::make('categories')
                            ->label('Kategoriler')
                            ->multiple()
                            ->relationship('categories', 'name', fn ($query) => $query->where('is_active', true))
                            ->preload()
                            ->searchable()
                            ->helperText('Bu ürünün hangi kategorilerde görüneceğini seçin.')
                            ->columnSpanFull(),

                        Textarea::make('short_description')
                            ->label('Kısa Açıklama')
                            ->helperText('Ürün listesi sayfasında gösterilecek kısa açıklama.')
                            ->rows(3)
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->label('Detaylı Açıklama')
                            ->helperText('Ürünün detay sayfasında gösterilecek tam açıklama.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('📷 Görseller')
                    ->schema([
                        FileUpload::make('main_image')
                            ->label('Ana Ürün Görseli')
                            ->helperText('Önerilen ölçü: 1000x1000 px (1:1 oran). Liste ve detay sayfalarında kullanılır.')
                            ->image()
                            ->directory('products')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->columnSpanFull(),

                        FileUpload::make('gallery_images')
                            ->label('Galeri Görselleri')
                            ->helperText('Önerilen ölçü: 1000x1000 px (1:1 oran). Detay sayfasında ek görseller olarak gösterilir.')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->directory('products/gallery')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->maxFiles(10)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('💰 Fiyat ve Stok')
                    ->schema([
                        TextInput::make('price')
                            ->label('Fiyat')
                            ->numeric()
                            ->prefix('₺')
                            ->helperText('Ürünün satış fiyatı.'),

                        TextInput::make('old_price')
                            ->label('Eski Fiyat (İndirimli ise)')
                            ->numeric()
                            ->prefix('₺')
                            ->helperText('Varsa eski fiyat, çizili olarak gösterilir.'),

                        TextInput::make('sku')
                            ->label('Stok Kodu (SKU)')
                            ->helperText('Ürün stok takip kodu.')
                            ->maxLength(255),

                        TextInput::make('stock')
                            ->label('Stok Miktarı')
                            ->numeric()
                            ->default(0)
                            ->helperText('Mevcut stok adedi.'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('⚙️ Yayın Ayarları')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Aktif mi?')
                            ->helperText('Aktif ürünler sitede görünür.')
                            ->default(true),
                    ])
                    ->collapsible(),

                Section::make('🔍 SEO Ayarları')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('SEO Başlığı')
                            ->helperText('Boş bırakılırsa ürün adı kullanılır.')
                            ->maxLength(255),

                        Textarea::make('meta_description')
                            ->label('SEO Açıklaması')
                            ->helperText('Arama motorlarında görünecek açıklama.')
                            ->rows(3)
                            ->maxLength(160),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('main_image')
                    ->label('Görsel'),

                TextColumn::make('name')
                    ->label('Ürün Adı')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('categories.name')
                    ->label('Kategoriler')
                    ->badge()
                    ->separator(','),

                TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('TRY')
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    }),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Aktif Durumu')
                    ->boolean()
                    ->trueLabel('Sadece aktif')
                    ->falseLabel('Sadece pasif')
                    ->native(false),

                SelectFilter::make('categories')
                    ->label('Kategori')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload(),

                Filter::make('out_of_stock')
                    ->label('Stokta Yok')
                    ->query(fn ($query) => $query->where('stock', 0)),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
