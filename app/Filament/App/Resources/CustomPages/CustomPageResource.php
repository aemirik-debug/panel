<?php

namespace App\Filament\App\Resources\CustomPages;

use App\Filament\App\Resources\CustomPages\Pages\CreateCustomPage;
use App\Filament\App\Resources\CustomPages\Pages\EditCustomPage;
use App\Filament\App\Resources\CustomPages\Pages\ListCustomPages;
use App\Filament\App\Resources\CustomPages\Schemas\CustomPageForm;
use App\Filament\App\Resources\CustomPages\Tables\CustomPagesTable;
use App\Filament\Traits\HasPackageModule;
use App\Models\Page;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomPageResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'pages';
    protected static ?string $model = Page::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function getNavigationLabel(): string
    {
        return 'Sayfalar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'SİTE YÖNETİMİ';
    }

    public static function getModelLabel(): string
    {
        return 'Sayfa';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Sayfalar';
    }

    public static function form(Schema $schema): Schema
    {
        return CustomPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomPagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomPages::route('/'),
            'create' => CreateCustomPage::route('/create'),
            'edit' => EditCustomPage::route('/{record}/edit'),
        ];
    }
}
