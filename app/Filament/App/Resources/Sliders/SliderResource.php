<?php

namespace App\Filament\App\Resources\Sliders;

use App\Filament\App\Resources\Sliders\Pages\CreateSlider;
use App\Filament\App\Resources\Sliders\Pages\EditSlider;
use App\Filament\App\Resources\Sliders\Pages\ListSliders;
use App\Filament\App\Resources\Sliders\Schemas\SliderForm;
use App\Filament\App\Resources\Sliders\Tables\SlidersTable;
use App\Filament\Traits\HasPackageModule;
use App\Models\Slider;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SliderResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'sliders';
    protected static ?string $model = Slider::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    // Bütün tip (Type) hatalarını önleyen garantili metodlar:
    protected static ?int $navigationSort = 60;

    public static function getNavigationLabel(): string
    {
        return 'Slider (Tam Ekran)';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Medya & Tasarım';
    }

    public static function getPluralLabel(): string
    {
        return 'Slider (Tam Ekran)';
    }

    public static function form(Schema $schema): Schema
    {
        return SliderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SlidersTable::configure($table);
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
            'index' => ListSliders::route('/'),
            'create' => CreateSlider::route('/create'),
            'edit' => EditSlider::route('/{record}/edit'),
        ];
    }
}