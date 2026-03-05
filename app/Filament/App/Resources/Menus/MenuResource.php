<?php

namespace App\Filament\App\Resources\Menus;

use App\Filament\App\Resources\Menus\Pages\CreateMenu;
use App\Filament\App\Resources\Menus\Pages\EditMenu;
use App\Filament\App\Resources\Menus\Pages\ListMenus;
use App\Filament\App\Resources\Menus\Schemas\MenuForm;
use App\Filament\App\Resources\Menus\Tables\MenusTable;
use App\Filament\Traits\HasPackageModule;
use App\Models\Menu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'menus';
    protected static ?string $model = Menu::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    public static function getNavigationLabel(): string
    {
        return 'Menüler';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'SİTE YÖNETİMİ';
    }

    public static function getModelLabel(): string
    {
        return 'Menü';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Menüler';
    }

    public static function form(Schema $schema): Schema
    {
        return MenuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenusTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}