<?php

namespace App\Filament\App\Resources\Contacts;

use App\Filament\App\Resources\Contacts\Pages;
use App\Models\Contact;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;

// İŞTE ÇÖZÜM BURADA: "Tables" klasörünü aradan çıkardık, senin sisteminin tanıdığı yolları yazdık
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\HtmlString;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    public static function getNavigationLabel(): string
    {
        return 'Formlardan Gelen Kayıtlar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Modüller';
    }

    public static function getPluralLabel(): string
    {
        return 'Formlardan Gelen Kayıtlar';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('form_name')
                    ->label('Form Adı')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('details')
                    ->label('Formdan Gelen Değerler')
                    ->html()
                    ->getStateUsing(function ($record) {
                        return new HtmlString("
                            <b>Ad Soyad:</b> {$record->name} <br>
                            <b>Cep Telefonu:</b> {$record->phone} <br>
                            <b>Email:</b> <a href='mailto:{$record->email}' style='color:blue;'>{$record->email}</a> <br>
                            <b>Mesaj:</b> {$record->message}
                        ");
                    }),

                TextColumn::make('created_at')
                    ->label('Ekleme Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextInputColumn::make('note')
                    ->label('Not')
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->filters([
                SelectFilter::make('form_name')
                    ->label('Form Seçiniz')
                    ->options([
                        'Bilgi Talep Formu' => 'Bilgi Talep Formu',
                        'İletişim Formu' => 'İletişim Formu',
                    ]),
                    
                SelectFilter::make('is_read')
                    ->label('İletişim Durumu')
                    ->options([
                        1 => 'İletişime Geçildi (Okundu)',
                        0 => 'İletişime Geçilmedi (Okunmadı)',
                    ])
            ])
            ->actions([
                // Orijinal Action sınıfımızla çalışan dinamik butonumuz
                Action::make('toggle_status')
                    ->label(fn ($record) => $record->is_read ? 'İletişime Geçildi' : 'İletişime Geçilmedi')
                    ->color(fn ($record) => $record->is_read ? 'success' : 'danger')
                    ->icon(fn ($record) => $record->is_read ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->button()
                    ->action(function ($record) {
                        $record->update(['is_read' => !$record->is_read]);
                    }),

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
            'index' => Pages\ListContacts::route('/'),
        ];
    }
}