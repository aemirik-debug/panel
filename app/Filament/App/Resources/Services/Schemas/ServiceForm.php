<?php

namespace App\Filament\App\Resources\Services\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ÜST KISIM → Temel Bilgiler + Görsel
                Grid::make(2)
                    ->columnSpanFull()
                    ->schema([

                        Section::make('Temel Bilgiler')
                            ->schema([

                                TextInput::make('title')
                                    ->label('Hizmet Adı')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) =>
                                        $set('slug', Str::slug($state))
                                    ),

                                TextInput::make('slug')
                                    ->label('URL Uzantısı')
                                    ->required()
                                    ->unique('services', 'slug', ignoreRecord: true)
                                    ->readOnly(),
                            ]),

                        Section::make('Görsel')
                            ->schema([

                                FileUpload::make('image')
                                    ->label('Hizmet Görseli')
                                    ->helperText('🖼️ Görsel otomatik olarak 800x600 boyutuna optimize edilecektir.')
                                    ->image()
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('4:3')
                                    ->imageResizeTargetWidth('800')
                                    ->imageResizeTargetHeight('600')
                                    ->maxSize(5120)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->directory('services')
                                    ->disk('public'),
                            ]),
                    ]),

                // Açıklamalar → TAM GENİŞLİK
                Section::make('Açıklamalar')
                    ->columnSpanFull()
                    ->schema([

                        Textarea::make('short_description')
                            ->label('Kısa Açıklama')
                            ->rows(2),

                        RichEditor::make('description')
                            ->label('Detaylı Açıklama')
                            ->toolbarButtons([
                                'bold','italic','link','bulletList',
                                'orderedList','h2','h3',
                                'attachFiles',
                                'undo','redo'
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('rich-editor/services')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                    ]),

                // İkon & Ayarlar → TAM GENİŞLİK
                Section::make('İkon & Ayarlar')
                    ->columnSpanFull()
                    ->schema([

                        Grid::make(3)->schema([

                            Select::make('icon')
                                ->label('Hizmet İkonu')
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->options([
                                    'bi bi-code-slash' => 'Yazılım',
                                    'bi bi-bag' => 'Tekstil',
                                    'bi bi-building' => 'İnşaat',
                                    'bi bi-heart-pulse' => 'Sağlık',
                                    'bi bi-truck' => 'Lojistik',
                                    'bi bi-cup-hot' => 'Restoran',
                                    'bi bi-shop' => 'Mağaza',
                                    'bi bi-graph-up-arrow' => 'Danışmanlık',
                                ])
                                ->allowHtml()
                                ->getOptionLabelUsing(fn ($value, $label) =>
                                    new HtmlString("
                                        <div style='display:flex;align-items:center;gap:8px;'>
                                            <i class='{$value}'></i>
                                            <span>{$label}</span>
                                        </div>
                                    ")
                                ),

                            TextInput::make('order')
                                ->label('Sıralama')
                                ->numeric()
                                ->default(0),

                            Toggle::make('is_active')
                                ->label('Yayında mı?')
                                ->default(true),
                        ]),
                    ]),

                // SEO
                Section::make('SEO Ayarları')
                    ->collapsed()
                    ->columnSpanFull()
                    ->schema([

                        TextInput::make('meta_title')
                            ->label('SEO Başlığı'),

                        Textarea::make('meta_description')
                            ->label('SEO Açıklaması')
                            ->rows(2),
                    ]),
            ]);
    }
}
