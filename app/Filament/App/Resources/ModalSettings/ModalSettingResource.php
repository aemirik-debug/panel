<?php

namespace App\Filament\App\Resources\ModalSettings;

use App\Filament\App\Resources\ModalSettings\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\ModalSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class ModalSettingResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'modal_settings';
    protected static ?string $model = ModalSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBell;

    // BURASI ÇOK ÖNEMLİ: Resimdeki gibi grupluyoruz
    public static function getNavigationGroup(): ?string
    {
        return 'Site Yönetimi';
    }

    public static function getNavigationLabel(): string
    {
        return 'Modal Ayarları';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Modal (Pop-up) Duyuru Ayarları')
                ->schema([
                    TextInput::make('title')->label('Başlık')->required(),
                    Textarea::make('content')->label('Duyuru Metni'),
                    TextInput::make('button_text')->label('Buton Yazısı'),
                    TextInput::make('button_link')->label('Buton Linki'),
                    Toggle::make('is_active')->label('Duyuru Yayında mı?')->default(false),
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            \Filament\Tables\Columns\TextColumn::make('title')->label('Başlık'),
            \Filament\Tables\Columns\ToggleColumn::make('is_active')->label('Durum'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModalSettings::route('/'),
            'create' => Pages\CreateModalSetting::route('/create'),
            'edit' => Pages\EditModalSetting::route('/{record}/edit'),
        ];
    }
}