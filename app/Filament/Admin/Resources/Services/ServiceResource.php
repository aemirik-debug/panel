<?php

namespace App\Filament\Admin\Resources\Services; 

use App\Models\Service;
use Filament\Schemas\Schema; // YENİ SİSTEM: Form bitti, Schema geldi!
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    // Sol Menü Ayarları (PHP 8.4 Uyumlu)
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase'; 
    protected static ?string $navigationLabel = 'Hizmetler';
    protected static \UnitEnum|string|null $navigationGroup = 'Kurumsal İçerikler'; 
    protected static ?int $navigationSort = 1; 

    // YENİ SİSTEM: Form $form yerine Schema $schema kullanıyoruz
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // ANA SEKME YAPISI (Müşteriyi boğmayan tasarım)
                Tabs::make('Hizmet Yönetimi')
                    ->tabs([
                        
                        // 1. SEKME: GENEL BİLGİLER
                        Tabs\Tab::make('Genel Bilgiler')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Hizmet Adı')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, \Filament\Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null)
                                    ->helperText('Hizmetin tam adını girin. Örn: Kurumsal Web Tasarım'),

                                TextInput::make('slug')
                                    ->label('URL (Otomatik Oluşur)')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Arama motorları için arka planda otomatik hazırlanır.'),

                                Textarea::make('short_description')
                                    ->label('Kısa Açıklama')
                                    ->rows(3)
                                    ->helperText('Anasayfadaki kutucukta görünecek vurucu özet.'),

                                RichEditor::make('content')
                                    ->label('Detaylı İçerik')
                                    ->columnSpanFull()
                                    ->helperText('Hizmetin detay sayfasında görünecek tam metin (Resim, video, kalın yazı ekleyebilirsiniz).'),
                            ])->columns(2),

                        // 2. SEKME: MEDYA
                        Tabs\Tab::make('Medya ve Görseller')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                FileUpload::make('icon')
                                    ->label('Hizmet İkonu')
                                    ->image()
                                    ->directory('services/icons')
                                    ->helperText('Hizmet kartında görünecek küçük ikon (Maks: 2MB).'),

                                FileUpload::make('image')
                                    ->label('Kapak Fotoğrafı')
                                    ->image()
                                    ->directory('services/images')
                                    ->columnSpanFull()
                                    ->helperText('Hizmetin detay sayfasına girildiğinde en üstte çıkacak büyük görsel.'),
                            ])->columns(2),

                        // 3. SEKME: SEO AYARLARI
                        Tabs\Tab::make('SEO (Arama Motoru)')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('Google Başlığı (Meta Title)')
                                    ->maxLength(60)
                                    ->helperText('Google aramalarında mavi renkle çıkacak başlık. Maksimum 60 karakter.'),

                                Textarea::make('meta_description')
                                    ->label('Google Açıklaması (Meta Description)')
                                    ->maxLength(160)
                                    ->rows(3)
                                    ->columnSpanFull()
                                    ->helperText('Müşterileri tıklamaya ikna edecek 160 karakterlik özet.'),
                            ]),
                    ])
                    ->columnSpanFull(),

                // YAYIN KONTROL PANELİ
                Section::make('Yayın Ayarları')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Sitede Yayınla')
                            ->default(true)
                            ->helperText('Kapatırsanız hizmet sitenizden anında gizlenir ama silinmez.'),

                        TextInput::make('sort_order')
                            ->label('Sıralama Numarası')
                            ->numeric()
                            ->default(0)
                            ->helperText('Hizmetleri sıralamak için (0, 1, 2...).'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon')
                    ->label('İkon')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Hizmet Adı')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Yayında'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Eklenme Tarihi')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}