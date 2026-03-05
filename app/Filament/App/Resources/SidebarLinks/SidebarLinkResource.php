<?php

namespace App\Filament\App\Resources\SidebarLinks;

use App\Filament\App\Resources\SidebarLinks\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\SidebarLink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\EditAction; // Direkt buradan çağırıyoruz
use Filament\Actions\DeleteAction; // Direkt buradan çağırıyoruz
use Filament\Actions\DeleteBulkAction; // Direkt buradan çağırıyoruz

class SidebarLinkResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'sidebar_links';
    protected static ?string $model = SidebarLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    public static function getNavigationLabel(): string
    {
        return 'İçerik Listeleme';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Modüller';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sidebar İçerik Bilgileri')
                    ->schema([
                        TextInput::make('page')
                            ->label('Bağlı Olduğu Sayfa')
                            ->placeholder('Örn: Hakkımızda'),
                        
                        TextInput::make('link_title')
                            ->label('Link / Slider Başlığı')
                            ->required(),
                        
                        TextInput::make('url')
                            ->label('Yönlendirilecek URL')
                            ->placeholder('https://...'),

                        TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('page')->label('Bağlı Olduğu Sayfa')->searchable(),
                TextColumn::make('link_title')->label('Link Başlığı')->searchable(),
                TextColumn::make('created_at')->label('Ekleme Tarihi')->dateTime('d.m.Y H:i'),
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
            'index' => Pages\ListSidebarLinks::route('/'),
            'create' => Pages\CreateSidebarLink::route('/create'),
            'edit' => Pages\EditSidebarLink::route('/{record}/edit'),
        ];
    }
}