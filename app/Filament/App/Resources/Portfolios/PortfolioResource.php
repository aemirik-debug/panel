<?php

namespace App\Filament\App\Resources\Portfolios;

use App\Filament\Traits\HasPackageModule;
use App\Models\Portfolio;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class PortfolioResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'portfolios';
    protected static ?string $model = Portfolio::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedViewfinderCircle;

    protected static ?int $navigationSort = 50;

    public static function getNavigationLabel(): string
    {
        return 'Projeler';
    }

    public static function getPluralLabel(): string
    {
        return 'Projeler';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Kurumsal İçerik';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Proje Detayları')
                    ->schema([
                        TextInput::make('title')
                            ->label('Proje Adı')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),

                        Textarea::make('description')
                            ->label('Proje Açıklaması')
                            ->rows(4)
                            ->columnSpanFull(),

                        FileUpload::make('featured_image')
                            ->label('Ana Görsel (800x600)')
                            ->image()
                            ->directory('portfolios')
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('600')
                            ->helperText('Proje kartında görünecek ana görsel.')
                            ->columnSpanFull(),

                        FileUpload::make('images')
                            ->label('Proje Galeri Görselleri')
                            ->image()
                            ->multiple()
                            ->directory('portfolios/gallery')
                            ->imageEditor()
                            ->maxFiles(10)
                            ->reorderable()
                            ->helperText('Modal içinde galeri olarak gösterilecek görseller. En fazla 10 görsel.')
                            ->columnSpanFull(),

                        TextInput::make('order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0)
                            ->helperText('Küçük sayılar önce görünür.'),

                        Toggle::make('is_active')
                            ->label('Yayınla')
                            ->default(true),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Görsel')
                    ->square()
                    ->size(56),

                TextColumn::make('title')
                    ->label('Proje Adı')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(50),

                TextColumn::make('order')
                    ->label('Sıra')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Yayında'),

                TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->defaultSort('order', 'asc')
            ->actions([
                EditAction::make()->label('Düzenle')->button(),
                DeleteAction::make()->label('Sil')->button(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPortfolios::route('/'),
            'create' => Pages\CreatePortfolio::route('/create'),
            'edit' => Pages\EditPortfolio::route('/{record}/edit'),
        ];
    }
}
