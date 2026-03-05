<?php

namespace App\Filament\App\Resources\Music; 

use App\Filament\App\Resources\Music\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\Music;
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
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class MusicResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'music';
    protected static ?string $model = Music::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMusicalNote;

    public static function getNavigationLabel(): string
    {
        return 'Müzik Çalar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Modüller';
    }

    public static function getPluralLabel(): string
    {
        return 'Müzik Çalarlar';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Müzik Bilgileri')
                    ->schema([
                        TextInput::make('page')
                            ->label('Bağlı Olduğu Sayfa')
                            ->placeholder('Örn: Anasayfa, Hakkımızda'),

                        TextInput::make('title')
                            ->label('Müzik Çalar Adı')
                            ->required()
                            ->maxLength(255),

                        FileUpload::make('file_path')
                            ->label('Ses Dosyası (MP3, WAV)')
                            ->directory('music_files')
                            ->acceptedFileTypes(['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg'])
                            ->required()
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Aktif (Sitede Çalsın)')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->searchable(),
                TextColumn::make('page')->label('Sayfa')->searchable()->placeholder('-'),
                TextColumn::make('title')->label('Müzik Çalar Adı')->searchable(),
                TextColumn::make('created_at')->label('Ekleme Tarihi')->dateTime('d.m.Y H:i')->sortable(),
                ToggleColumn::make('is_active')->label('Aktif'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('page')
                    ->label('İçeriğin Bağlı Olduğu Sayfayı Seçiniz')
                    ->options(fn () => Music::pluck('page', 'page')->toArray()),
            ])
            ->actions([
                EditAction::make()->label('Düzenle')->button(),
                DeleteAction::make()->label('Sil')->button(),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->label('Seçilileri Sil'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMusic::route('/'),
            'create' => Pages\CreateMusic::route('/create'),
            'edit' => Pages\EditMusic::route('/{record}/edit'),
        ];
    }
}