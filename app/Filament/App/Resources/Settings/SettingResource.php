<?php

namespace App\Filament\App\Resources\Settings;

use App\Filament\App\Resources\Settings\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\Setting;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SettingResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'settings';

    protected static ?string $model = Setting::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 100;

    public static function getNavigationGroup(): ?string
    {
        return 'Yapılandırma';
    }

    public static function getNavigationLabel(): string
    {
        return 'Site Ayarları';
    }

    public static function canCreate(): bool
    {
        return ! Setting::exists();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Site Ayarlari')
                    ->persistTabInQueryString('ayar-sekmesi')
                    ->tabs([
                        Tab::make('Genel')
                            ->schema([
                                Section::make('Temel Bilgiler')
                                    ->description('Site kimlik bilgileri ve temel iletisim alanlari.')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('site_name')
                                                ->label('Site Adi')
                                                ->required()
                                                ->maxLength(255),

                                            TextInput::make('phone')
                                                ->label('Telefon')
                                                ->tel()
                                                ->maxLength(30),

                                            TextInput::make('email')
                                                ->label('E-Posta')
                                                ->email()
                                                ->maxLength(255),

                                            TextInput::make('address')
                                                ->label('Adres')
                                                ->maxLength(255)
                                                ->columnSpanFull(),
                                        ]),
                                    ]),

                                Section::make('Logo ve Favicon')
                                    ->description('Sitede kullanilan temel marka gorselleri.')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            FileUpload::make('logo')
                                                ->label('Logo')
                                                ->helperText('🖼️ Logo otomatik olarak 400x200 boyutuna optimize edilecektir.')
                                                ->image()
                                                ->imageResizeMode('contain')
                                                ->imageResizeTargetWidth('400')
                                                ->imageResizeTargetHeight('200')
                                                ->maxSize(2048)
                                                ->disk('public')
                                                ->directory('settings')
                                                ->visibility('public')
                                                ->fetchFileInformation(false)
                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),

                                            FileUpload::make('favicon')
                                                ->label('Favicon')
                                                ->helperText('🖼️ Favicon otomatik olarak 64x64 boyutuna optimize edilecektir.')
                                                ->image()
                                                ->imageResizeMode('contain')
                                                ->imageResizeTargetWidth('64')
                                                ->imageResizeTargetHeight('64')
                                                ->maxSize(512)
                                                ->disk('public')
                                                ->directory('settings')
                                                ->visibility('public')
                                                ->fetchFileInformation(false)
                                                ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/vnd.microsoft.icon']),
                                        ]),
                                    ]),

                                Section::make('Iletisim Formu Bildirimleri')
                                    ->schema([
                                        Toggle::make('send_contact_notifications')
                                            ->label('Form mesajlarini e-posta ile bildir')
                                            ->default(false)
                                            ->live()
                                            ->inline(false),

                                        TextInput::make('contact_notification_email')
                                            ->label('Bildirim E-Posta Adresi')
                                            ->email()
                                            ->maxLength(255)
                                            ->placeholder('ornek@firma.com')
                                            ->visible(fn (Get $get) => (bool) $get('send_contact_notifications'))
                                            ->required(fn (Get $get) => (bool) $get('send_contact_notifications'))
                                            ->helperText('Acik oldugunda iletisim formu gonderimleri bu adrese iletilir.'),
                                    ]),
                            ]),

                        Tab::make('Ana Sayfa')
                            ->schema([
                                Section::make('Ana Sayfa Alan Sirasi ve Gorunurluk')
                                    ->description('Slider her zaman 0. siradadir. Asagidaki alanlari surukleyerek siralayip gorunurlugu acip kapatabilirsiniz.')
                                    ->schema([
                                        Repeater::make('home_sections')
                                            ->label('Alandaki Bolumler')
                                            ->default(Setting::getDefaultHomeSections())
                                            ->reorderableWithDragAndDrop()
                                            ->addable(false)
                                            ->deletable(false)
                                            ->columns(2)
                                            ->live()
                                            ->schema([
                                                Hidden::make('label')
                                                    ->dehydrated(false),

                                                Select::make('key')
                                                    ->label('Bolum')
                                                    ->options(Setting::HOME_SECTION_DEFINITIONS)
                                                    ->native(false)
                                                    ->disabled()
                                                    ->dehydrated(true),

                                                Toggle::make('is_visible')
                                                    ->label('Gorunsun')
                                                    ->default(true)
                                                    ->inline(false),
                                            ]),

                                        Placeholder::make('section_preview')
                                            ->label('Canli Onizleme')
                                            ->content(function (Get $get) {
                                                $sections = $get('home_sections') ?? Setting::getDefaultHomeSections();
                                                
                                                // Slider her zaman sabit
                                                $preview = '<div style="font-family: sans-serif; background: #f9fafb; padding: 12px; border-radius: 6px; border: 1px solid #e5e7eb;">';
                                                $preview .= '<div style="margin-bottom: 8px; font-weight: 600; color: #374151;">📌 Ana Sayfa Sıralama Önizlemesi:</div>';
                                                $preview .= '<ol style="margin: 0; padding-left: 20px; color: #6b7280;">';
                                                $preview .= '<li style="margin: 4px 0;"><strong style="color: #059669;">🔒 Slider</strong> <span style="font-size: 11px; color: #9ca3af;">(sabit)</span></li>';
                                                
                                                foreach ($sections as $index => $section) {
                                                    $label = $section['label'] ?? Setting::HOME_SECTION_DEFINITIONS[$section['key']] ?? $section['key'];
                                                    $isVisible = $section['is_visible'] ?? true;
                                                    $status = $isVisible 
                                                        ? '<span style="color: #059669;">✓ Görünür</span>' 
                                                        : '<span style="color: #dc2626;">✗ Gizli</span>';
                                                    
                                                    $preview .= sprintf(
                                                        '<li style="margin: 4px 0;"><strong>%s</strong> <span style="font-size: 11px;">%s</span></li>',
                                                        htmlspecialchars($label),
                                                        $status
                                                    );
                                                }
                                                
                                                $preview .= '</ol></div>';
                                                
                                                return new \Illuminate\Support\HtmlString($preview);
                                            })
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Ana Sayfa Yazi Alanlari')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('services_section_title')
                                                ->label('Hizmetler Bolumu Basligi')
                                                ->default('Hizmetlerimiz')
                                                ->maxLength(255),

                                            Textarea::make('services_description')
                                                ->label('Hizmetler Bolumu Aciklamasi')
                                                ->rows(3),

                                            TextInput::make('cta_title')
                                                ->label('Harekete Gec Basligi')
                                                ->default('Harekete Gec')
                                                ->maxLength(255),

                                            Textarea::make('cta_description')
                                                ->label('Harekete Gec Aciklamasi')
                                                ->rows(3),

                                            TextInput::make('references_section_title')
                                                ->label('Referanslar Bolum Basligi')
                                                ->default('Musteri Referanslari')
                                                ->maxLength(255),

                                            Textarea::make('references_section_description')
                                                ->label('Referanslar Bolum Aciklamasi')
                                                ->default('Bizimle calisan musterilerimizin degerlendirmeleri')
                                                ->rows(3),
                                        ]),

                                        Toggle::make('show_home_gallery_button')
                                            ->label('Ana Sayfada "Tum Galeriyi Goruntule" butonu gorunsun')
                                            ->default(true)
                                            ->inline(false),
                                    ]),
                            ]),

                        Tab::make('SEO ve Footer')
                            ->schema([
                                Section::make('SEO Ayarlari')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('SEO Basligi')
                                            ->maxLength(255),

                                        Textarea::make('meta_description')
                                            ->label('SEO Aciklamasi')
                                            ->rows(2),

                                        TextInput::make('site_keywords')
                                            ->label('Anahtar Kelimeler')
                                            ->placeholder('kurumsal site, web tasarim, ajans')
                                            ->maxLength(255),

                                        Textarea::make('google_analytics')
                                            ->label('Google Analytics Kodu')
                                            ->rows(3)
                                            ->placeholder('G-XXXXXXXXXX veya script kodu'),
                                    ]),

                                Section::make('Footer')
                                    ->schema([
                                        Textarea::make('footer_text')
                                            ->label('Footer Yazisi')
                                            ->rows(3),

                                        TextInput::make('portfolio_section_title')
                                            ->label('Projeler Bolum Basligi')
                                            ->default('Projelerimiz')
                                            ->maxLength(255),

                                        Textarea::make('portfolio_section_description')
                                            ->label('Projeler Bolum Aciklamasi')
                                            ->default('Gerceklestirdigimiz basarili projeler ve calismalarimiz')
                                            ->rows(2),
                                    ]),
                            ]),

                        Tab::make('E-posta Sunucu')
                            ->schema([
                                Section::make('Mail Sunucu Ayarlari')
                                    ->description('Formlari kendi mail sunucunuz uzerinden gondermek icin bu bolumu doldurun. Bos birakildiktan .env dosyasindaki ayarlar kullanilir.')
                                    ->schema([
                                        Toggle::make('use_custom_mail_settings')
                                            ->label('Ozel mail ayarlarini kullan')
                                            ->helperText('Bu aktif oldugunda asagidaki mail ayarlari kullanilacak.')
                                            ->default(false)
                                            ->live()
                                            ->inline(false),

                                        Grid::make(2)->schema([
                                            Select::make('mail_driver')
                                                ->label('Mail Surucu')
                                                ->options([
                                                    'smtp' => 'SMTP (Gmail, Outlook, cPanel)',
                                                    'log' => 'Log (Test - Gercekte gondermez)',
                                                ])
                                                ->default('smtp')
                                                ->native(false)
                                                ->required(fn (Get $get) => (bool) $get('use_custom_mail_settings'))
                                                ->visible(fn (Get $get) => (bool) $get('use_custom_mail_settings')),

                                            Select::make('mail_encryption')
                                                ->label('Sifreleme')
                                                ->options([
                                                    'tls' => 'TLS (Onerilir)',
                                                    'ssl' => 'SSL',
                                                ])
                                                ->default('tls')
                                                ->native(false)
                                                ->required(fn (Get $get) => (bool) $get('use_custom_mail_settings'))
                                                ->visible(fn (Get $get) => (bool) $get('use_custom_mail_settings')),
                                        ]),

                                        Grid::make(2)->schema([
                                            TextInput::make('mail_host')
                                                ->label('SMTP Sunucu Adresi')
                                                ->placeholder('smtp.gmail.com, smtp.office365.com, mail.site.com')
                                                ->helperText('Gmail: smtp.gmail.com | Outlook: smtp.office365.com')
                                                ->required(fn (Get $get) => (bool) $get('use_custom_mail_settings'))
                                                ->visible(fn (Get $get) => (bool) $get('use_custom_mail_settings')),

                                            TextInput::make('mail_port')
                                                ->label('Port')
                                                ->numeric()
                                                ->default(587)
                                                ->helperText('TLS icin 587, SSL icin 465')
                                                ->required(fn (Get $get) => (bool) $get('use_custom_mail_settings'))
                                                ->visible(fn (Get $get) => (bool) $get('use_custom_mail_settings')),
                                        ]),

                                        Grid::make(2)->schema([
                                            TextInput::make('mail_username')
                                                ->label('Kullanici Adi / E-posta')
                                                ->placeholder('info@firmaniz.com')
                                                ->email()
                                                ->required(fn (Get $get) => (bool) $get('use_custom_mail_settings'))
                                                ->visible(fn (Get $get) => (bool) $get('use_custom_mail_settings')),

                                            TextInput::make('mail_password')
                                                ->label('Sifre')
                                                ->password()
                                                ->revealable()
                                                ->helperText('Gmail icin "Uygulama Sifresi" olusturun.')
                                                ->dehydrated(fn ($state) => filled($state))
                                                ->required(fn (Get $get) => (bool) $get('use_custom_mail_settings'))
                                                ->visible(fn (Get $get) => (bool) $get('use_custom_mail_settings')),
                                        ]),

                                        Grid::make(2)->schema([
                                            TextInput::make('mail_from_address')
                                                ->label('Gonderen Adresi')
                                                ->email()
                                                ->placeholder('info@firmaniz.com')
                                                ->helperText('Maillerin hangi adresten gonderilecegi')
                                                ->required(fn (Get $get) => (bool) $get('use_custom_mail_settings'))
                                                ->visible(fn (Get $get) => (bool) $get('use_custom_mail_settings')),

                                            TextInput::make('mail_from_name')
                                                ->label('Gonderen Adi')
                                                ->placeholder('Firma Adı')
                                                ->helperText('Mailerde gorunecek gonderen ismi')
                                                ->required(fn (Get $get) => (bool) $get('use_custom_mail_settings'))
                                                ->visible(fn (Get $get) => (bool) $get('use_custom_mail_settings')),
                                        ]),
                                    ]),
                            ]),

                        Tab::make('Tema')
                            ->schema([
                                Section::make('Tema Ayarlari')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            ColorPicker::make('primary_color')
                                                ->label('Ana Renk')
                                                ->required(),

                                            ColorPicker::make('secondary_color')
                                                ->label('Ikincil Renk'),
                                        ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
