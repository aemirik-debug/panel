<?php

namespace App\Filament\App\Resources\Menus\Schemas;

use App\Models\Menu;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Alert;
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
                    ->description('Menünüzün temel bilgilerini burada ayarlayın.')
                    ->schema([
                        Alert::make('menu_structure_info')
                            ->title('💡 Menü Yapısı Hakkında')
                            ->description('
                                Menü sistemi hiyerarşik olarak çalışır:
                                • **Ana Menü**: Bir menüyü seçmezseniz, ana menü olur
                                • **Alt Menü**: Bir menü seçerseniz, o menünün altında yer alır
                                • **Sıralama**: Aynı seviye menüleri bu sayı ile sıralarsınız (küçükten büyüğe)
                            ')
                            ->icon('heroicon-o-information-circle')
                            ->visible(fn() => true),

                        Select::make('parent_id')
                            ->label('📌 Üst Menü Seçiniz')
                            ->options(
                                Menu::where('parent_id', null)
                                    ->orderBy('order')
                                    ->pluck('title', 'id')
                            )
                            ->searchable()
                            ->clearable()
                            ->placeholder('— Ana Menü —')
                            ->helperText('Eğer bu menüyü başka bir menünün altında göstermek istiyorsanız, o menüyü seçin.'),

                        TextInput::make('title')
                            ->label('📝 Menü Başlığı')
                            ->placeholder('Örn: Hakkımızda, Hizmetler, İletişim')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (\Filament\Forms\Set $set, ?string $state) {
                                if ($state) {
                                    $set('slug', Str::slug($state));
                                    $set('meta_title', $state);
                                }
                            })
                            ->helperText('Menü başlığı sayfada görünecek metindir. Kısa ve özlü tutun.'),

                        TextInput::make('order')
                            ->label('📊 Sıralama Numarası')
                            ->numeric()
                            ->default(0)
                            ->helperText('Küçükten büyüğe sıralanır. Örn: 1, 2, 3...'),

                    ])->columns(2),

                Section::make('🔗 Bağlantı Ayarları')
                    ->description('Menüye tıkladığında nereye gitmesi gerektiğini belirtin.')
                    ->schema([
                        Alert::make('url_examples')
                            ->title('📌 Link Örnekleri')
                            ->description('
                                **İç Sayfalar:**
                                • `/` – Anasayfa
                                • `/hakkimizda` – Hakkımızda
                                • `/hizmetler` – Hizmetler
                                • `/hizmet/web-tasarimi` – Hizmet detayı
                                • `/blog` – Blog
                                • `/iletisim` – İletişim
                                
                                **Dış Linkler:**
                                • `https://example.com`
                                • `https://facebook.com/yourpage`
                            ')
                            ->icon('heroicon-o-link'),

                        TextInput::make('url')
                            ->label('🌐 Bağlantı (URL)')
                            ->placeholder('Örn: /hakkimizda veya https://example.com')
                            ->required()
                            ->maxLength(500)
                            ->helperText('Menüye tıkladığında gitmesi gereken sayfa adresi.'),

                        TextInput::make('slug')
                            ->label('🔤 SEO Slug')
                            ->placeholder('Otomatik doldurulur')
                            ->disabled()
                            ->maxLength(255)
                            ->helperText('Otomatik olarak başlıktan oluşturulur.'),

                    ])->columns(2),

                Section::make('📌 SEO & Açıklamalar')
                    ->description('Arama motorları ve ziyaretçiler için bilgiler.')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('📄 SEO Başlığı (Meta Title)')
                            ->placeholder('Otomatik doldurulur')
                            ->maxLength(60)
                            ->helperText('30-60 karakter idealdir.'),

                        Textarea::make('meta_description')
                            ->label('📝 SEO Açıklaması')
                            ->placeholder('Bu menü sayfasının kısa açıklaması.')
                            ->maxLength(160)
                            ->helperText('120-160 karakter idealdir.')
                            ->rows(3),

                        Textarea::make('description')
                            ->label('📖 Menü Açıklaması')
                            ->placeholder('İsteğe bağlı.')
                            ->helperText('Zorunlu değildir.')
                            ->rows(3),

                        TextInput::make('icon')
                            ->label('🎨 Bootstrap Icon')
                            ->placeholder('Örn: arrow-right, star-fill')
                            ->helperText('Bootstrap Icons kullanabilirsiniz.'),

                    ])->columns(2),

                Section::make('⚙️ Durum')
                    ->description('Menünün yayında olup olmadığını kontrol edin.')
                    ->schema([
                        Alert::make('active_status')
                            ->title('ℹ️ Aktif/Pasif Durumu')
                            ->description('Menüyü pasif yaparsanız, web sitesinde görünmez.')
                            ->icon('heroicon-o-information-circle'),

                        Toggle::make('is_active')
                            ->label('✅ Bu menüyü web sitesinde göster')
                            ->default(true)
                            ->inline(false),

                    ])->columns(1),
            ]);
    }
}
