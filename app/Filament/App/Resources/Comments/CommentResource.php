<?php

namespace App\Filament\App\Resources\Comments;

use App\Filament\App\Resources\Comments\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\Comment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class CommentResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'comments';
    protected static ?string $model = Comment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    public static function getNavigationLabel(): string
    {
        return 'Yorumlar';
    }

    // HATAYI ÇÖZEN KISIM: Grup ismini mülkiyet olarak değil, fonksiyon olarak verdik
   public static function getNavigationGroup(): ?string
    {
        return 'SİTE YÖNETİMİ';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Müşteri Yorumu')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name_surname')
                            ->label('İsim Soyisim')
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('comment')
                            ->label('Yorum Metni')
                            ->required(),
                        \Filament\Forms\Components\Toggle::make('is_active')
                            ->label('Onaylı mı?')
                            ->default(true),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('name_surname')->label('Müşteri')->searchable(),
                TextColumn::make('comment')->label('Yorum')->limit(50),
                ToggleColumn::make('is_active')->label('Durum'),
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y'),
            ])
            ->actions([
                EditAction::make()->label('Düzenle')->button(),
                DeleteAction::make()->label('Sil')->button(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}