<?php

namespace App\Filament\App\Resources\Services;

use App\Filament\App\Resources\Services\Pages\CreateService;
use App\Filament\App\Resources\Services\Pages\EditService;
use App\Filament\App\Resources\Services\Pages\ListServices;
use App\Filament\App\Resources\Services\Schemas\ServiceForm;
use App\Filament\App\Resources\Services\Tables\ServicesTable;
use App\Filament\Traits\HasPackageModule;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema; // Temel sınıf bu
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'services';
    protected static ?string $model = Service::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $recordTitleAttribute = 'title';

    // Bütün tip hatalarını önleyen garantili metodlar:
    protected static ?int $navigationSort = 30;

    public static function getNavigationLabel(): string
    {
        return 'Hizmetler';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'İçerik Yönetimi';
    }

    public static function getPluralLabel(): string
    {
        return 'Hizmetler';
    }

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}