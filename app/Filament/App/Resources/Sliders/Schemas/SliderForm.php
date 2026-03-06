<?php

namespace App\Filament\App\Resources\Sliders\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema; 

class SliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Slider Modeli Seçimi')
                    ->description('Sitenizde kullanmak istediğiniz slider türünü seçin.')
                    ->schema([
                        Radio::make('slider_model')
                            ->label('Hangi slider modelini kullanmak istersiniz?')
                            ->options([
                                'full_width' => '1. Slide Modeli (Tam Genişlik)',
                                'split_layout' => '2. Slide Modeli (Bölünmüş Yapı)',
                            ])
                            ->descriptions([
                                'full_width' => 'Tam genişlikte tek büyük slider. Başlık, alt metin ve buton alanları ile kullanılır.',
                                'split_layout' => 'Solda büyük kayar slider, sağda iki sabit görsel ve açıklama alanları ile kullanılır.',
                            ])
                            ->columns(2)
                            ->live()
                            ->required(),
                    ])
                    ->columnSpanFull(),
                
                // ==========================================
                // MODEL 1: TAM GENISLIK SLIDER
                // ==========================================
                Section::make('📸 Slider Görselleri ve İçerikleri')
                    ->description('Tam genişlik slider için görsellerinizi ve içeriklerinizi ekleyin. + butonuna basarak yeni slide ekleyebilirsiniz.')
                    ->visible(fn (Get $get) => $get('slider_model') === 'full_width')
                    ->schema([
                        Repeater::make('slides')
                            ->label('')
                            ->schema([
                                FileUpload::make('image')
                                    ->label('📷 Slide Görseli')
                                    ->helperText('Onerilen olcu: 1920x900 px (tam genislik banner). Tum slide gorsellerinde ayni oran kullanin.')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml', 'image/avif'])
                                    ->maxSize(5120)
                                    ->fetchFileInformation(false)
                                    ->directory('sliders')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->required()
                                    ->columnSpanFull(),

                                TextInput::make('title')
                                    ->label('Ana Başlık')
                                    ->helperText('Boş bırakılırsa görsel üstünde başlık gösterilmez.')
                                    ->columnSpanFull(),

                                TextInput::make('subtitle')
                                    ->label('Alt Metin / İçerik')
                                    ->helperText('Boş bırakılırsa alt metin gösterilmez.')
                                    ->columnSpanFull(),

                                TextInput::make('button_text')
                                    ->label('Buton Yazısı')
                                    ->placeholder('Örn: Başlayın, İncele, Detaylar'),

                                TextInput::make('button_url')
                                    ->label('Buton Linki')
                                    ->url()
                                    ->placeholder('Örn: https://example.com'),
                            ])
                            ->defaultItems(1)
                            ->minItems(1)
                            ->maxItems(10)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (?array $state): string => 
                                !empty($state['title'] ?? null) ? $state['title'] : 'Slide')
                            ->addActionLabel('+ Yeni Slide Ekle')
                            ->columns(2),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),
                
                // ==========================================
                // MODEL 2: SPLIT LAYOUT (Sol Kayar + Sag Sabit)
                // ==========================================
                Section::make('🎬 Sol Taraf - Kayar Slider Görselleri')
                    ->description('Solda görünecek kayar slider görsellerinizi ekleyin. + butonuna basarak yeni slide ekleyebilirsiniz.')
                    ->visible(fn (Get $get) => $get('slider_model') === 'split_layout')
                    ->schema([
                        Repeater::make('slides')
                            ->label('')
                            ->schema([
                                FileUpload::make('image')
                                    ->label('📷 Sol Büyük Görsel')
                                    ->helperText('Onerilen olcu: 1600x900 px (16:9). Sol kayar alanda en iyi gorunum icin yatay gorsel yukleyin.')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml', 'image/avif'])
                                    ->maxSize(5120)
                                    ->fetchFileInformation(false)
                                    ->directory('sliders')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->required()
                                    ->columnSpanFull(),

                                TextInput::make('title')
                                    ->label('Ana Başlık')
                                    ->helperText('Boş bırakılırsa başlık gösterilmez.')
                                    ->columnSpanFull(),

                                TextInput::make('subtitle')
                                    ->label('Alt Metin / İçerik')
                                    ->helperText('Boş bırakılırsa alt metin gösterilmez.')
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(1)
                            ->minItems(1)
                            ->maxItems(10)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (?array $state): string => 
                                !empty($state['title'] ?? null) ? $state['title'] : 'Slide')
                            ->addActionLabel('+ Yeni Slide Ekle')
                            ->columns(1),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),

                Section::make('🖼️ Sağ Taraf - Sabit Görseller')
                    ->description('Sağ tarafta sabit olarak görünecek üst ve alt görselleri yükleyin.')
                    ->visible(fn (Get $get) => $get('slider_model') === 'split_layout')
                    ->schema([
                        FileUpload::make('right_top_image')
                            ->label('📷 Sağ Üst Sabit Görsel')
                            ->helperText('Onerilen olcu: 900x450 px (2:1). Sag ust kartta sabit gorunur.')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml', 'image/avif'])
                            ->maxSize(5120)
                            ->fetchFileInformation(false)
                            ->directory('sliders')
                            ->disk('public')
                            ->visibility('public')
                            ->columnSpanFull(),

                        TextInput::make('right_top_caption')
                            ->label('Sağ Üst Görsel Altı Slogan')
                            ->helperText('Boş ise slogan gösterilmez.')
                            ->columnSpanFull(),

                        FileUpload::make('right_bottom_image')
                            ->label('📷 Sağ Alt Sabit Görsel')
                            ->helperText('Onerilen olcu: 900x450 px (2:1). Sag alt kartta sabit gorunur.')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml', 'image/avif'])
                            ->maxSize(5120)
                            ->fetchFileInformation(false)
                            ->directory('sliders')
                            ->disk('public')
                            ->visibility('public')
                            ->columnSpanFull(),

                        TextInput::make('right_bottom_caption')
                            ->label('Sağ Alt Görsel Altı Slogan')
                            ->helperText('Boş ise slogan gösterilmez.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),

                Section::make('⚙️ Yayın Ayarları')
                    ->description('Slider\'ınızı aktif veya pasif duruma getirebilirsiniz.')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Slider Aktif mi?')
                            ->helperText('Aktif edilirse anasayfada görünür.')
                            ->default(true),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}