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
use Filament\Tables\Columns\ImageColumn;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;

class CommentResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'comments';
    protected static ?string $model = Comment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    public static function getNavigationLabel(): string
    {
        return 'Referanslar';
    }

    public static function getPluralLabel(): string
    {
        return 'Referanslar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Kurumsal İçerik';
    }

    protected static ?int $navigationSort = 40;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Referans Detayları')
                    ->schema([
                        TextInput::make('name_surname')
                            ->label('Firma Adi')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('position')
                            ->label('Proje / Hizmet Basligi')
                            ->helperText('Orn: Kurumsal Web Sitesi, E-Ticaret Donusumu')
                            ->maxLength(255),

                        FileUpload::make('image')
                            ->label('Firma Logosu (250x250)')
                            ->image()
                            ->directory('referrals')
                            // Tum gorselleri merkezden kirp ve 250x250 standardina getir.
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('250')
                            ->imageResizeTargetHeight('250')
                            ->helperText('Sistem logoyu ortalayip 250x250 olarak kaydeder.'),

                        \Filament\Forms\Components\Textarea::make('comment')
                            ->label('Kisa Proje Aciklamasi')
                            ->required()
                            ->rows(5),

                        \Filament\Forms\Components\Toggle::make('is_active')
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
                ImageColumn::make('image')
                    ->label('Görüntü')
                    ->square()
                    ->size(56),

                TextColumn::make('name_surname')
                    ->label('Firma')
                    ->searchable(),

                TextColumn::make('position')
                    ->label('Proje')
                    ->limit(30),

                TextColumn::make('comment')
                    ->label('Aciklama')
                    ->limit(50),

                ToggleColumn::make('is_active')
                    ->label('Yayında'),

                TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y')
                    ->sortable(),
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