<?php

namespace App\Filament\App\Resources\Settings;

use App\Filament\App\Resources\Settings\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\Setting;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;


class SettingResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'settings';
    public static function getNavigationGroup(): ?string
    {
        return 'Sistem & Ayarlar';
    }

    public static function getNavigationLabel(): string
    {
        return 'Site Ayarları';
    }
    protected static ?string $model = Setting::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 100;

    // 🔥 TEK KAYIT OLSUN
    public static function canCreate(): bool
    {
        return ! Setting::exists();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Genel Ayarlar')
                    ->schema([

                        Grid::make(2)->schema([

                            TextInput::make('site_name')
                                ->label('Site Adı')
                                ->required(),

                            TextInput::make('phone')
                                ->label('Telefon'),

                            TextInput::make('whatsapp_number')
                                ->label('WhatsApp Numarası')
                                ->helperText('Örn: 905001234567 (ülke kodu ile birlikte, başında + olmadan)')
                                ->placeholder('905001234567'),

                            TextInput::make('email')
                                ->label('E-Posta'),

                            TextInput::make('address')
                                ->label('Adres')
                                ->columnSpanFull(),

                            FileUpload::make('logo')
                                ->label('Logo')
                                ->helperText('Onerilen olcu: 200x100 px. Logo bu boyutta yuklenmelidir.')
                                ->image()
                                ->directory('settings'),

                            FileUpload::make('favicon')
                                ->label('Favicon')
                                ->image()
                                ->directory('settings'),
                        ]),
                    ]),

                Section::make('Sosyal Medya')
                    ->collapsed()
                    ->schema([

                        TextInput::make('facebook'),
                        TextInput::make('instagram'),
                        TextInput::make('twitter'),
                        TextInput::make('linkedin'),
                    ]),

                Section::make('Footer & SEO')
                    ->collapsed()
                    ->schema([

                        Textarea::make('footer_text')
                            ->label('Footer Yazısı')
                            ->rows(2),

                        TextInput::make('meta_title')
                            ->label('SEO Başlığı'),

                        Textarea::make('meta_description')
                            ->label('SEO Açıklaması')
                            ->rows(2),
                    ]),

                    Section::make('Ana Sayfa Bolumleri')
                        ->schema([
                            TextInput::make('services_section_title')
                                ->label('Hizmetler Bolumu Basligi')
                                ->default('Hizmetlerimiz')
                                ->maxLength(255),

                            Textarea::make('services_description')
                                ->label('Hizmetler Bolumu Aciklamasi')
                                ->rows(2),

                            TextInput::make('cta_title')
                                ->label('Harekete Gec Basligi')
                                ->default('Harekete Gec')
                                ->maxLength(255),

                            Textarea::make('cta_description')
                                ->label('Harekete Gec Aciklamasi')
                                ->rows(2),
                        ]),
					Section::make('Tema Ayarları')
					->collapsed()
					->schema([

						Grid::make(2)->schema([

							ColorPicker::make('primary_color')
								->label('Ana Renk')
								->required(),

							ColorPicker::make('secondary_color')
								->label('İkincil Renk'),
						]),
					]),

                Section::make('İletişim Formu Bildirimleri')
                    ->collapsed()
                    ->schema([
                        Toggle::make('send_contact_notifications')
                            ->label('Formdan gelen mesajları e-posta ile bildir')
                            ->default(false)
                            ->inline(false),

                        TextInput::make('contact_notification_email')
                            ->label('Bildirim E-Posta Adresi')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('ornek@firma.com')
                            ->visible(fn (Get $get) => (bool) $get('send_contact_notifications'))
                            ->helperText('Açık olduğunda iletişim formu gönderimleri bu adrese iletilir.'),
                    ]),

                Section::make('Referanslar Sayfası')
                    ->collapsed()
                    ->schema([
                        TextInput::make('references_section_title')
                            ->label('Bölüm Başlığı')
                            ->default('Müşteri Referansları')
                            ->maxLength(255)
                            ->helperText('Referanslar sayfası ve ana sayfadaki referanslar alanı başlığı.'),

                        Textarea::make('references_section_description')
                            ->label('Bölüm Açıklaması')
                            ->default('Bizimle çalışan müşterilerimizin değerlendirmeleri')
                            ->rows(2)
                            ->helperText('Referanslar sayfası ve ana sayfadaki referanslar alanı açıklaması.'),
                    ]),

                Section::make('Projeler Sayfası')
                    ->collapsed()
                    ->schema([
                        TextInput::make('portfolio_section_title')
                            ->label('Bölüm Başlığı')
                            ->default('Projelerimiz')
                            ->maxLength(255)
                            ->helperText('Projeler sayfasındaki ana başlık.'),

                        Textarea::make('portfolio_section_description')
                            ->label('Bölüm Açıklaması')
                            ->default('Gerçekleştirdiğimiz başarılı projeler ve çalışmalarımız')
                            ->rows(2)
                            ->helperText('Başlığın altında görünecek kısa açıklama.'),
                    ]),

            ]);
    }

    // 🔥 TABLOYU SİLİYORUZ (liste istemiyoruz)
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
