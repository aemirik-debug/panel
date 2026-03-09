<?php

namespace App\Filament\App\Resources\Post;

use App\Filament\App\Resources\Post\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\Post;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'posts';
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function getNavigationLabel(): string
    {
        return 'Yazılar / Blog';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Kurumsal İçerik';
    }

    protected static ?int $navigationSort = 60;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('İçerik Bilgileri')
                    ->schema([
                        TextInput::make('title')
                            ->label('Sayfa / İçerik Başlığı')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                        TextInput::make('slug')
                            ->label('URL (Slug)')
                            ->required()
                            ->unique(Post::class, 'slug', ignoreRecord: true),

                        RichEditor::make('content')
                            ->label('İçerik Detayı')
                            ->toolbarButtons([
                                'bold','italic','link','bulletList',
                                'orderedList','h2','h3',
                                'attachFiles',
                                'undo','redo'
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('rich-editor/posts')
                            ->fileAttachmentsVisibility('public')
                            ->required()
                            ->columnSpanFull(),

                        FileUpload::make('image_path')
                            ->label('Kapak Görseli')
                            ->helperText('🖼️ Görsel otomatik olarak 1200x675 boyutuna optimize edilecektir.')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('675')
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->directory('posts')
                            ->disk('public')
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Yayında mı?')
                            ->default(true),
                    ])->columns(2),
                
                Section::make('SEO Ayarları')
                    ->description('Google aramalarında sayfanın nasıl görüneceğini belirleyin.')
                    ->schema([
                        TextInput::make('meta_title')->label('SEO Başlığı'),
                        TextInput::make('meta_description')->label('SEO Açıklaması'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')->label('Görsel')->circular(),
                TextColumn::make('title')->label('Başlık')->searchable()->sortable(),
                TextColumn::make('slug')->label('URL'),
                ToggleColumn::make('is_active')->label('Durum'),
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y'),
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
            'index' => Pages\ListPost::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}