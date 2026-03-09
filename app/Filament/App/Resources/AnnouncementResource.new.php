<?php

namespace App\Filament\App\Resources;

use App\Models\Announcement;
use Filament\Forms\Components\DateTimePickerComponent;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;
    protected static ?int $navigationSort = 120;

    public static function getNavigationLabel(): string
    {
        return 'Duyurular';
    }

    public static function getPluralLabel(): string
    {
        return 'Duyurular';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Pazarlama & İçerik';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Temel Bilgiler')
                    ->description('Duyurunun başlığını ve içeriğini tanımlayın.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Duyuru Başlığı')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label('Duyuru İçeriği')
                            ->toolbarButtons([
                                'bold', 'italic', 'link', 'bulletList',
                                'orderedList', 'h2', 'h3',
                                'attachFiles',
                                'undo', 'redo'
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('announcements/content')
                            ->fileAttachmentsVisibility('public')
                            ->required()
                            ->columnSpanFull(),

                        FileUpload::make('image')
                            ->label('Duyuru Görseli')
                            ->helperText('🖼️ Görsel otomatik olarak 600x400 boyutuna optimize edilecektir.')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('3:2')
                            ->imageResizeTargetWidth('600')
                            ->imageResizeTargetHeight('400')
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->directory('announcements')
                            ->disk('public')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Buton Ayarları')
                    ->description('Duyurunun altına opsiyonel buton ekleyin.')
                    ->schema([
                        TextInput::make('button_text')
                            ->label('Buton Yazısı')
                            ->placeholder('Örn: Detayları Gör, Hemen Başla'),

                        TextInput::make('button_url')
                            ->label('Buton Linki')
                            ->url()
                            ->placeholder('https://example.com'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Görüntü Ayarları')
                    ->description('Duyurunun nasıl gösterileceğini belirleyin.')
                    ->schema([
                        Select::make('type')
                            ->label('Duyuru Türü')
                            ->options([
                                'modal' => '🪟 Modal (Popup penceresi)',
                                'banner' => '🎨 Banner (Sayfa üstü)',
                                'popup' => '💬 Popup (Köşede çıkan pencere)',
                            ])
                            ->default('modal')
                            ->required(),

                        Select::make('color_scheme')
                            ->label('Renk Şeması')
                            ->options([
                                'primary' => '🔵 Mavi (Birincil)',
                                'success' => '🟢 Yeşil (Başarı)',
                                'warning' => '🟡 Sarı (Uyarı)',
                                'danger' => '🔴 Kırmızı (Tehlike)',
                                'info' => '⚪ Gri (Bilgi)',
                            ])
                            ->default('primary')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Yayın Tarihleri')
                    ->description('Duyurunun ne zaman gösterilmesi gerektiğini belirleyin.')
                    ->schema([
                        DateTimePickerComponent::make('starts_at')
                            ->label('Gösterim Başlangıcı')
                            ->helperText('Boş bırakırsa hemen gösterilir.'),

                        DateTimePickerComponent::make('ends_at')
                            ->label('Gösterim Bitişi')
                            ->helperText('Boş bırakırsa sınırsız gösterilir.'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Durum')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Yayında')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Görsel')
                    ->square()
                    ->size(48),

                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tür')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'modal' => 'blue',
                        'banner' => 'purple',
                        'popup' => 'orange',
                        default => 'gray',
                    }),

                TextColumn::make('color_scheme')
                    ->label('Renk')
                    ->badge(),

                TextColumn::make('view_count')
                    ->label('Görüntüleme')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Yayında')
                    ->boolean(),

                TextColumn::make('starts_at')
                    ->label('Başl. Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                EditAction::make()->label('Düzenle')->button(),
                DeleteAction::make()->label('Sil')->button(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
