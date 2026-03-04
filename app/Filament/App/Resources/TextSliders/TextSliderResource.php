<?php

namespace App\Filament\App\Resources\TextSliders;

use App\Filament\App\Resources\TextSliders\Pages;
use App\Models\TextSlider;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class TextSliderResource extends Resource
{
    protected static ?string $model = TextSlider::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;

    public static function getNavigationLabel(): string
    {
        return 'Metin Slider';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Modüller';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Slider İçeriği')
                    ->schema([
                        TextInput::make('page')->label('Sayfa')->placeholder('Örn: Anasayfa'),
                        TextInput::make('title')->label('Ana Başlık')->required(),
                        TextInput::make('subtitle')->label('Alt Başlık'),
                        FileUpload::make('image_path')
                            ->label('Görsel')
                            ->directory('sliders')
                            ->image()
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('button_text')->label('Buton Yazısı'),
                        TextInput::make('button_link')->label('Buton Linki'),
                        TextInput::make('sort_order')->label('Sıra')->numeric()->default(0),
                        Toggle::make('is_active')->label('Aktif')->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')->label('Görsel')->circular(),
                TextColumn::make('title')->label('Başlık')->searchable(),
                TextColumn::make('page')->label('Sayfa'),
                ToggleColumn::make('is_active')->label('Durum'),
            ])
            ->actions([
                EditAction::make()->label('Düzenle')->button(),
                DeleteAction::make()->label('Sil')->button(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTextSliders::route('/'),
            'create' => Pages\CreateTextSlider::route('/create'),
            'edit' => Pages\EditTextSlider::route('/{record}/edit'),
        ];
    }
}