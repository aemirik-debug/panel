<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Pages\CreateTenant;
use App\Filament\Admin\Resources\Pages\EditTenant;
use App\Filament\Admin\Resources\Pages\ListTenants;
use App\Models\Tenant;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput; // Form komponentleri hala Forms namespace'inde
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section; // V4'te Schemas namespace'inde
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use BackedEnum;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = 'Müşteriler';

    // Schema sınıfını kullan ve ->components() metodu ile döndür
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Müşteri Kimlik Bilgileri')
                    ->schema([
                        TextInput::make('id')
                            ->label('Müşteri Kodu (Küçük harf, boşluksuz)')
                            ->disabled(fn ($record) => $record !== null) // Düzenlerken ID değişmesin
                            ->placeholder('musteri1')
                            ->helperText('Örnek: musteri1, firmaismi, vb.')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->dehydrated(fn ($record) => $record === null), // Sadece yeni kayıtta gönder
                    ])
                    ->columns(1),

                Section::make('📦 PAKET SEÇİMİ')
                    ->description('Müşterinize sunmak istediğiniz paketi seçin. Modüller otomatik aktif olacaktır.')
                    ->schema([
                        Radio::make('package')
                            ->label('Paket Türü')
                            ->options([
                                'baslangic' => '🟢 BAŞLANGIÇ PAKETİ - Temel özellikler (Hizmetler, İletişim, Galeri, Slider)',
                                'profesyonel' => '🟡 PROFESYONEL PAKETİ - Başlangıç + Blog, Yorumlar, Kategoriler',
                                'kurumsal' => '🔴 KURUMSAL PAKETİ - Tüm modüller (17 modül aktif)',
                            ])
                            ->descriptions([
                                'baslangic' => '✓ Hizmetler ✓ İletişim ✓ Galeri ✓ Slider ✓ Ayarlar ✓ Menü',
                                'profesyonel' => '✓ Başlangıç özellikleri + ✓ Blog ✓ Kategoriler ✓ Yorumlar ✓ Sidebar',
                                'kurumsal' => '✓ Tüm modüller: Blog, Etkinlik, Anket, Müzik, Harita, Modal, vb.',
                            ])
                            ->default('baslangic')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, $state) {
                                // Paket seçildiğinde modülleri otomatik doldur
                                $modules = Tenant::getPackageModules($state);
                                $set('modules', $modules);
                            }),

                        Placeholder::make('module_info')
                            ->label('Aktif Olacak Modüller')
                            ->content(function (Get $get) {
                                $package = $get('package') ?? 'baslangic';
                                $modules = Tenant::getPackageModules($package);
                                
                                $moduleLabels = [
                                    'services' => '🛠️ Hizmetler',
                                    'contacts' => '📨 İletişim Formları',
                                    'galleries' => '🖼️ Galeriler',
                                    'sliders' => '🎬 Slider',
                                    'settings' => '⚙️ Site Ayarları',
                                    'menus' => '🧭 Menü Yönetimi',
                                    'posts' => '📝 Blog/İçerik',
                                    'categories' => '📂 Kategoriler',
                                    'comments' => '💬 Yorumlar',
                                    'sidebar_links' => '🔗 Sidebar Linkleri',
                                    'events' => '📅 Etkinlikler',
                                    'quizzes' => '📊 Anketler',
                                    'quiz_results' => '📊 Anket Sonuçları',
                                    'music' => '🎵 Müzik/Video',
                                    'maps' => '🗺️ Haritalar',
                                    'text_sliders' => '🎬 Text Slider',
                                    'modal_settings' => '🪟 Modal Ayarları',
                                    'users' => '👥 Kullanıcı Yönetimi',
                                ];
                                
                                $list = collect($modules)
                                    ->map(fn($m) => $moduleLabels[$m] ?? $m)
                                    ->implode(' • ');
                                
                                return $list ?: 'Modül seçilmedi';
                            }),

                        // Hidden field to store modules array
                        Hidden::make('modules'),
                    ])
                    ->columns(1),

                Section::make('🎨 TEMA VE GÖRÜNÜM')
                    ->schema([
                        Select::make('theme')
                            ->label('Web Sitesi Teması')
                            ->options([
                                'theme_1' => '🎨 Modern Kurumsal - (Koyu mavi tonlar, profesyonel)',
                                'theme_2' => '🎨 Minimalist Ajans - (Temiz, sade çizgiler)',
                                'theme_3' => '🎨 Kreatif Portfolyo - (Renkli, dinamik)',
                            ])
                            ->default('theme_1')
                            ->required(),
                    ])
                    ->columns(1)
                    ->collapsed(),
                
                Section::make('🌐 DOMAIN TANIMI')
                    ->schema([
                        Repeater::make('domains')
                            ->relationship('domains')
                            ->schema([
                                TextInput::make('domain')
                                    ->label('Domain Adı')
                                    ->placeholder('musteri1.test')
                                    ->helperText('Herd kullanıyorsanız otomatik .test domain üretir')
                                    ->required(),
                            ])
                            ->minItems(1)
                            ->maxItems(1)
                            ->addable(false)
                            ->deletable(false),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Müşteri Kodu')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('package')
                    ->label('Paket')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'baslangic' => 'success',
                        'profesyonel' => 'warning',
                        'kurumsal' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'baslangic' => 'BAŞLANGIÇ',
                        'profesyonel' => 'PROFESYONEL',
                        'kurumsal' => 'KURUMSAL',
                        default => strtoupper($state),
                    }),
                
                TextColumn::make('domains.domain')
                    ->label('Bağlı Domain')
                    ->badge()
                    ->color('info'),

                TextColumn::make('theme')
                    ->label('Tema')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'theme_1' => '🎨 Modern',
                        'theme_2' => '🎨 Minimal',
                        'theme_3' => '🎨 Kreatif',
                        default => $state,
                    })
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Kayıt Tarihi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
            'edit' => EditTenant::route('/{record}/edit'),
        ];
    }
}