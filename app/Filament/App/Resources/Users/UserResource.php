<?php

namespace App\Filament\App\Resources\Users; // Klasörüne uygun namespace

use App\Filament\App\Resources\Users\Pages; // Klasörüne uygun namespace
use App\Filament\Traits\HasPackageModule;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'users';
    protected static ?string $model = User::class;

    // HATAYI ÇÖZEN KISIM: Tip tanımını tam olarak Filament'in istediği formata getirdik
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 110;

    public static function getNavigationGroup(): ?string
    {
        return 'Yapılandırma';
    }

    public static function getNavigationLabel(): string
    {
        return 'Yöneticiler';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Yönetici Bilgileri')
                    ->schema([
                        TextInput::make('name')
                            ->label('Kullanıcı Adı')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Adresi')
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->label('Şifre')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                    ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('Kullanıcı Adı')->searchable(),
                TextColumn::make('email')->label('Email Adresi')->searchable(),
            ])
            ->actions([
                EditAction::make()->label('Düzenle')->button(),
                DeleteAction::make()->label('Sil')->button(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}