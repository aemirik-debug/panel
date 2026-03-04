<?php

namespace App\Filament\App\Resources\Services;

use App\Filament\App\Resources\Services\Pages\CreateService;
use App\Filament\App\Resources\Services\Pages\EditService;
use App\Filament\App\Resources\Services\Pages\ListServices;
use App\Filament\App\Resources\Services\Schemas\ServiceForm;
use App\Filament\App\Resources\Services\Tables\ServicesTable;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema; // Temel sınıf bu
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $recordTitleAttribute = 'title';

    // Bütün tip hatalarını önleyen garantili metodlar:
    public static function getNavigationLabel(): string
    {
        return 'Hizmetler';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Modüller';
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