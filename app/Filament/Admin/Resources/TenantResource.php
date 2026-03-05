<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Pages\CreateTenant;
use App\Filament\Admin\Resources\Pages\EditTenant;
use App\Filament\Admin\Resources\Pages\ListTenants;
use App\Models\Tenant;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput; // Form komponentleri hala Forms namespace'inde
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section; // V4'te Schemas namespace'inde
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use BackedEnum;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = 'Müşteriler';

    // Schema sınıfını kullan ve ->components() metodu ile döndür
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Müşteri Kimlik Bilgileri')
                    ->schema([
                        TextInput::make('id')
                            ->label('Müşteri Kodu (Küçük harf, boşluksuz)')
                            ->disabled(fn ($record) => $record !== null) // Düzenlerken ID değişmesin
                            ->placeholder('musteri2')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ]),

                    Section::make('Yapılandırma (Modüller & Tema)')
                ->schema([
                    Select::make('theme')
                        ->label('Web Sitesi Teması')
                        ->options([
                            'theme_1' => 'Modern Kurumsal',
                            'theme_2' => 'Minimalist Ajans',
                            'theme_3' => 'Kreatif Portfolyo',
                        ])
                        ->default('theme_1')
                        ->required(),

                    CheckboxList::make('modules')
                        ->label('Aktif Modüller')
                        ->options([
                            'blog' => 'Blog Yazıları',
                            'services' => 'Hizmetler Modülü',
                            'portfolio' => 'Portfolyo / Galeri',
                            'team' => 'Ekibimiz',
                        ])
                        ->columns(2),
                ]),
                
                Section::make('Domain Tanımlama')
                    ->schema([
                        Repeater::make('domains')
                            ->relationship('domains')
                            ->schema([
                                TextInput::make('domain')
                                    ->label('Domain Adı')
                                    ->placeholder('musteri2.test')
                                    ->required(),
                            ])
                            ->minItems(1)
                            ->maxItems(1)
                            ->addable(false)
                            ->deletable(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Müşteri Kodu')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('domains.domain')
                    ->label('Bağlı Domain')
                    ->badge()
                    ->color('success'),

                TextColumn::make('created_at')
                    ->label('Kayıt Tarihi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
            'edit' => EditTenant::route('/{record}/edit'),
        ];
    }
}