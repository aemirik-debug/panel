<?php

namespace App\Filament\App\Resources\Menus\Schemas;

use App\Models\Menu;
use App\Models\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('📋 Menü Yapısı')
                    ->description('Menünüzün temel bilgilerini burada ayarlayın. Menü sistemi hiyerarşik olarak çalışır: Ana Menü (seçim yapmazsa), Alt Menü (bir menü seçerseniz), Sıralama (aynı seviye menüleri sıralamak için).')
                    ->schema([
                        Select::make('parent_id')
                            ->label('📌 Üst Menü Seçiniz')
                            ->options(
                                Menu::where('parent_id', null)
                                    ->orderBy('order')
                                    ->pluck('title', 'id')
                            )
                            ->searchable()
                            ->nullable()
                            ->placeholder('— Ana Menü —')
                            ->helperText('Eğer bu menüyü başka bir menünün altında göstermek istiyorsanız, o menüyü seçin.'),

                        TextInput::make('title')
                            ->label('📝 Menü Başlığı')
                            ->placeholder('Örn: Hakkımızda, Hizmetler, İletişim')
                            ->default(fn () => Menu::query()->exists() ? null : 'ANASAYFA')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if ($state) {
                                    $set('slug', Str::slug($state));
                                    $set('meta_title', $state);
                                }
                            })
                            ->helperText('Menü başlığı sayfada görünecek metindir. Kısa ve özlü tutun.'),

                        Select::make('menu_type')
                            ->label('🧭 Sayfa Tipi')
                            ->options([
                                'home' => 'Ana Sayfa',
                                'about' => 'Hakkımızda',
                                'services' => 'Hizmetler',
                                'references' => 'Referanslar',
                                'portfolio' => 'Projeler',
                                'blog' => 'Blog',
                                'contact' => 'İletişim',
                                'custom_page' => 'Özel Sayfa (Paneldeki Sayfalar)',
                                'custom_url' => 'Özel URL',
                            ])
                            ->default(fn () => Menu::query()->exists() ? 'custom_url' : 'home')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                if ($state === 'custom_page') {
                                    $set('url', null);

                                    return;
                                }

                                if ($state === 'custom_url') {
                                    return;
                                }

                                $route = Menu::routeByMenuType((string) $state);

                                if (filled($route)) {
                                    $set('page_id', null);
                                    $set('url', $route);
                                }
                            })
                            ->helperText('Standart sayfalar için URL otomatik atanır.'),

                        TextInput::make('order')
                            ->label('📊 Sıralama Numarası')
                            ->numeric()
                            ->default(fn () => Menu::query()->exists() ? ((int) Menu::query()->max('order') + 1) : 0)
                            ->helperText('Küçükten büyüğe sıralanır. Örn: 1, 2, 3...'),

                    ])->columns(2),

                Section::make('🔗 Bağlantı Ayarları')
                    ->description('Menüye tıkladığında nereye gitmesi gerektiğini belirtin. İç Sayfalar: /, /hakkimizda, /hizmetler. Dış Linkler: https://example.com')
                    ->schema([
                        Select::make('page_id')
                            ->label('📄 Mevcut Sayfadan Seç')
                            ->options(Page::where('is_active', true)->orderBy('title')->pluck('title', 'id'))
                            ->searchable()
                            ->nullable()
                            ->disabled(fn (Get $get) => $get('menu_type') !== 'custom_page')
                            ->required(fn (Get $get) => $get('menu_type') === 'custom_page')
                            ->helperText('Bir sayfa seçerseniz URL alanı otomatik doldurulur.')
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                if (filled($state)) {
                                    $page = Page::find($state);
                                    if ($page) {
                                        $set('url', '/' . ltrim($page->slug, '/'));
                                    }
                                }
                            }),

                        TextInput::make('url')
                            ->label('🌐 Bağlantı (URL)')
                            ->placeholder('Örn: /hakkimizda veya https://example.com')
                            ->default(fn () => Menu::query()->exists() ? null : '/')
                            ->required(fn (Get $get) => $get('menu_type') !== 'custom_page')
                            ->disabled(fn (Get $get) => in_array($get('menu_type'), ['home', 'about', 'services', 'references', 'portfolio', 'blog', 'contact'], true))
                            ->maxLength(500)
                            ->helperText('Özel URL tipinde manuel girilir, standart tiplerde otomatik atanır.'),

                        TextInput::make('slug')
                            ->label('🔤 SEO Slug')
                            ->placeholder('Otomatik doldurulur')
                            ->disabled()
                            ->maxLength(255)
                            ->helperText('Otomatik olarak başlıktan oluşturulur.'),

                    ])->columns(2),

                Section::make('📌 SEO & Açıklamalar')
                    ->description('Arama motorları ve ziyaretçiler için bilgiler. Meta Title: 30-60 karakter, Meta Description: 120-160 karakter.')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('📄 SEO Başlığı (Meta Title)')
                            ->placeholder('Otomatik doldurulur')
                            ->maxLength(60)
                            ->helperText('Arama motorlarında gösterilecek başlık. 30-60 karakter idealdir.'),

                        Textarea::make('meta_description')
                            ->label('📝 SEO Açıklaması (Meta Description)')
                            ->placeholder('Bu menü sayfasının kısa açıklaması.')
                            ->maxLength(160)
                            ->helperText('Arama motorlarında gösterilecek açıklama. 120-160 karakter idealdir.')
                            ->rows(3),

                        Textarea::make('description')
                            ->label('📖 Menü Açıklaması')
                            ->placeholder('İsteğe bağlı.')
                            ->helperText('Bu bilgi sitemizdeki bu menüyü açıklanması için kullanılabilir. Zorunlu değildir.')
                            ->rows(3),

                        TextInput::make('icon')
                            ->label('🎨 Bootstrap Icon')
                            ->placeholder('Örn: arrow-right, star-fill')
                            ->helperText('Bootstrap Icons kullanabilirsiniz. Örn: "bi bi-arrow-right"'),

                    ])->columns(2),

                Section::make('⚙️ Durum')
                    ->description('Menüyü pasif yaparsanız, web sitesinde görünmez. Yalnızca yönetim panelinde görülür.')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('✅ Bu menüyü web sitesinde göster')
                            ->default(true)
                            ->inline(false),

                    ])->columns(1),
            ]);
    }
}
