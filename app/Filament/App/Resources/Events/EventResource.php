<?php

namespace App\Filament\App\Resources\Events;

use App\Filament\App\Resources\Events\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\Event;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

// KART TASARIMI İÇİN YENİ EKLENENLER
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class EventResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'events';
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    public static function getNavigationLabel(): string
    {
        return 'Etkinlik Takvimi';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Diğer Özellikler';
    }

    protected static ?int $navigationSort = 130;

    public static function getPluralLabel(): string
    {
        return 'Etkinlikler';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Etkinlik Detayları')
                    ->schema([
                        TextInput::make('title')
                            ->label('Etkinlik Adı')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('location')
                            ->label('Etkinlik Yeri / Adres')
                            ->maxLength(255),

                        DateTimePicker::make('start_date')
                            ->label('Başlangıç Tarihi ve Saati')
                            ->required()
                            ->native(false)
                            ->displayFormat('d.m.Y H:i'),

                        DateTimePicker::make('end_date')
                            ->label('Bitiş Tarihi ve Saati')
                            ->native(false)
                            ->displayFormat('d.m.Y H:i')
                            ->after('start_date'),

                        Textarea::make('description')
                            ->label('Etkinlik Açıklaması')
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Yayında mı?')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // STACK: Bilgileri alt alta, şık bir kart formatında dizer
                Stack::make([
                    TextColumn::make('title')
                        ->weight('bold') // Başlığı kalın yapar
                        ->size('lg')     // Yazıyı büyütür
                        ->searchable(),

                    TextColumn::make('start_date')
                        ->dateTime('d.m.Y H:i')
                        ->icon('heroicon-o-calendar') // Yanına takvim ikonu koyar
                        ->color('primary')            // Mavi renkle vurgular
                        ->sortable(),

                    TextColumn::make('location')
                        ->icon('heroicon-o-map-pin') // Yanına harita ikonu koyar
                        ->color('gray')
                        ->placeholder('Konum belirtilmedi')
                        ->searchable(),

                    ToggleColumn::make('is_active')
                        ->label('Aktif'),
                ])->space(3), // Kart içi öğeler arasına boşluk bırakır
            ])
            // GRID: Bu kodu ekleyince liste alt alta değil, yan yana kutular(kartlar) halinde dizilir!
            ->contentGrid([
                'md' => 2, // Orta ekranlarda yan yana 2 kart
                'xl' => 3, // Büyük ekranlarda yan yana 3 kart
            ])
            ->defaultSort('start_date', 'desc')
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}