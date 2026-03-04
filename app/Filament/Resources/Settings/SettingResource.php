<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\Pages;
use App\Models\Setting;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ColorPicker;


class SettingResource extends Resource
{
    // BU FONKSİYONU EKLE, ESKİ SATIRI SİL
    public static function getNavigationGroup(): ?string
    {
        return 'SİTE YÖNETİMİ';
    }

    public static function getNavigationLabel(): string
    {
        return 'Site Ayarları';
    }
    protected static ?string $model = Setting::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Ayarları';
    protected static ?int $navigationSort = 1;

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

                            TextInput::make('email')
                                ->label('E-Posta'),

                            TextInput::make('address')
                                ->label('Adres')
                                ->columnSpanFull(),

                            FileUpload::make('logo')
                                ->label('Logo')
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
					Section::make('Ana Sayfa Hero Alanı')
					->schema([

						TextInput::make('hero_title')
							->label('Başlık'),

						Textarea::make('hero_subtitle')
							->label('Alt Başlık')
							->rows(2),

						TextInput::make('hero_button_text')
							->label('Buton Yazısı'),

						TextInput::make('hero_button_link')
							->label('Buton Linki'),

						FileUpload::make('hero_background')
							->label('Arka Plan Görseli')
							->image()
							->directory('settings'),
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
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
