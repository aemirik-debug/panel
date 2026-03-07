<?php

namespace App\Filament\App\Resources\Contacts;

use App\Filament\App\Resources\Contacts\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\Contact;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

// İŞTE ÇÖZÜM BURADA: "Tables" klasörünü aradan çıkardık, senin sisteminin tanıdığı yolları yazdık
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class ContactResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'contacts';
    protected static ?string $model = Contact::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    public static function getNavigationLabel(): string
    {
        return 'Form Kayıtları';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'İletişim & Etkileşim';
    }

    protected static ?int $navigationSort = 10;

    public static function getPluralLabel(): string
    {
        return 'Form Kayıtları';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Ad Soyad')
                ->disabled(),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->disabled(),

            TextInput::make('phone')
                ->label('Telefon')
                ->disabled(),

            TextInput::make('subject')
                ->label('Konu')
                ->disabled(),

            Textarea::make('message')
                ->label('Mesaj')
                ->disabled()
                ->rows(6),

            Textarea::make('note')
                ->label('Not')
                ->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable(),

                TextColumn::make('subject')
                    ->label('Konu')
                    ->searchable(),

                TextColumn::make('new_badge')
                    ->label('Durum')
                    ->badge()
                    ->color('danger')
                    ->state(fn (Contact $record): string => $record->is_read ? '' : 'Yeni'),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->actions([
                EditAction::make()
                    ->label('Detay')
                    ->button()
                    ->icon('heroicon-o-pencil')
                    ->modalHeading(fn ($record) => "İletişim Formu - {$record->name}")
                    ->fillForm(function (Contact $record): array {
                        if (! $record->is_read) {
                            $record->update(['is_read' => true]);
                        }

                        return $record->attributesToArray();
                    })
                    ->form([
                        TextInput::make('name')
                            ->label('Ad Soyad')
                            ->disabled(),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->disabled(),

                        TextInput::make('phone')
                            ->label('Telefon')
                            ->disabled(),

                        TextInput::make('subject')
                            ->label('Konu')
                            ->disabled(),

                        Textarea::make('message')
                            ->label('Mesaj')
                            ->disabled()
                            ->rows(6),

                        Textarea::make('note')
                            ->label('Not')
                            ->rows(3),
                    ]),

                DeleteAction::make()
                    ->label('Sil')
                    ->button(),
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
            'index' => Pages\ListContacts::route('/'),
        ];
    }
}