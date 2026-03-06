<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\PostResource\Pages;
use App\Models\Post;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('📝 Yazı İçeriği')
                    ->schema([
                        TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('URL (Slug)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Otomatik olarak başlıktan oluşturulur.')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('excerpt')
                            ->label('Kısa Özet')
                            ->helperText('Liste sayfalarında gösterilecek kısa açıklama.')
                            ->rows(3)
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label('İçerik')
                            ->required()
                            ->helperText('Yazının tam içeriği.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('📷 Görsel')
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label('Ana Görsel')
                            ->helperText('Önerilen ölçü: 1200x800 px (3:2 oran). Liste ve detay sayfalarında kullanılır.')
                            ->image()
                            ->directory('posts')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('⚙️ Yayın Ayarları')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Aktif mi?')
                            ->helperText('Aktif ise sitede görünür, pasif ise gizlenir.')
                            ->default(true)
                            ->live(),

                        DateTimePicker::make('published_at')
                            ->label('Yayın Tarihi')
                            ->default(now())
                            ->native(false)
                            ->visible(fn (Get $get) => $get('is_published')),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('🔍 SEO Ayarları')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('SEO Başlığı')
                            ->helperText('Boş bırakılırsa yazı başlığı kullanılır.')
                            ->maxLength(255),

                        Textarea::make('meta_description')
                            ->label('SEO Açıklaması')
                            ->helperText('Arama motorlarında görünecek açıklama.')
                            ->rows(3)
                            ->maxLength(160),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Görsel'),

                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                ToggleColumn::make('is_published')
                    ->label('Aktif')
                    ->onIcon('heroicon-m-check')
                    ->offIcon('heroicon-m-x-mark'),

                TextColumn::make('published_at')
                    ->label('Yayın Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Aktif/Pasif')
                    ->boolean()
                    ->trueLabel('Sadece aktif')
                    ->falseLabel('Sadece pasif')
                    ->native(false),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
