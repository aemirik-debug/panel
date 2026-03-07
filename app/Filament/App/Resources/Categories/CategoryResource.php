<?php

namespace App\Filament\App\Resources\Categories;

use App\Filament\App\Resources\Categories\Pages\CreateCategory;
use App\Filament\App\Resources\Categories\Pages\EditCategory;
use App\Filament\App\Resources\Categories\Pages\ListCategories;
use App\Filament\Traits\HasPackageModule;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class CategoryResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'categories';
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // Bütün tip hatalarını önleyen garantili metodlar:
    public static function getNavigationLabel(): string
    {
        return 'Ürün Kategorileri';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Ürün Yönetimi';
    }

    protected static ?int $navigationSort = 20;

    public static function getPluralLabel(): string
    {
        return 'Ürün Kategorileri';
    }

    public static function form(Schema $schema): Schema
    {
       return $schema
        ->components([
            \Filament\Schemas\Components\Section::make('Kategori Bilgileri')
                ->schema([
                    TextInput::make('name')
                        ->label('Kategori Adı')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) =>
                                $set('slug', Str::slug($state))
                            ),

                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
         return $table
        ->columns([
            TextColumn::make('name')
                ->label('Kategori Adı')
                ->searchable(),

            TextColumn::make('slug'),

            TextColumn::make('created_at')
                ->label('Ekleme Tarihi')
                ->dateTime('d.m.Y H:i'),
        ])
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->bulkActions([
            DeleteBulkAction::make(),
        ]);
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}