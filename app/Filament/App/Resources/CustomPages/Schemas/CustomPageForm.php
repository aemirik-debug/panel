<?php

namespace App\Filament\App\Resources\CustomPages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CustomPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('📄 Sayfa İçeriği')
                    ->description('Bu alanda menülere bağlayabileceğiniz özel sayfa içerikleri oluşturabilirsiniz.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Sayfa Başlığı')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                if (filled($state)) {
                                    $set('slug', Str::slug($state));
                                    $set('meta_title', $state);
                                }
                            })
                            ->helperText('Örn: Kurumsal Kimlik, KVKK, Gizlilik Politikası'),

                        TextInput::make('slug')
                            ->label('URL Uzantısı')
                            ->required()
                            ->maxLength(255)
                            ->unique('pages', 'slug', ignoreRecord: true)
                            ->helperText('Örn: kurumsal-kimlik. Sayfa linki: /kurumsal-kimlik'),

                        Textarea::make('excerpt')
                            ->label('Kısa Özet')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->helperText('Opsiyonel: sayfa açıklaması veya giriş metni.'),

                        RichEditor::make('content')
                            ->label('Sayfa İçeriği')
                            ->toolbarButtons([
                                'bold','italic','link','bulletList',
                                'orderedList','h2','h3',
                                'attachFiles',
                                'undo','redo'
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('rich-editor/pages')
                            ->fileAttachmentsVisibility('public')
                            ->required()
                            ->columnSpanFull(),

                        FileUpload::make('featured_image')
                            ->label('Kapak Görseli')
                            ->helperText('🖼️ Görsel otomatik olarak 1200x675 boyutuna optimize edilecektir.')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('675')
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->directory('pages')
                            ->disk('public')
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Sayfa Yayında')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),

                Section::make('🔎 SEO Bilgileri')
                    ->description('Arama motorlarında görünen başlık ve açıklamayı yönetin.')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60)
                            ->helperText('Önerilen uzunluk: 30-60 karakter.'),

                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('Önerilen uzunluk: 120-160 karakter.'),
                    ])
                    ->columns(2),
            ]);
    }
}
