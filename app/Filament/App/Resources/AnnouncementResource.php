<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

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
        return 'İletişim & Etkileşim';
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
                            ->helperText('Instagram formati: 1080x1350px (4:5). Otomatik olarak optimize edilecektir. Max: 5MB')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('4:5')
                            ->imageResizeTargetWidth('1080')
                            ->imageResizeTargetHeight('1350')
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
                        DateTimePicker::make('starts_at')
                            ->label('Gösterim Başlangıcı')
                            ->helperText('Boş bırakırsa hemen gösterilir.'),

                        DateTimePicker::make('ends_at')
                            ->label('Gösterim Bitişi')
                            ->helperText('Boş bırakırsa sınırsız gösterilir. Başlangıçtan önce olamaz.')
                            ->afterOrEqual('starts_at'),
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
                    ->disk('public')
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

                TextColumn::make('schedule_status')
                    ->label('Plan Durumu')
                    ->getStateUsing(function (Announcement $record): string {
                        $now = now();

                        if (! $record->is_active) {
                            return 'Pasif';
                        }

                        if ($record->starts_at && $record->starts_at->isFuture()) {
                            return 'Yaklasiyor';
                        }

                        if ($record->ends_at && $record->ends_at->lt($now)) {
                            return 'Suresi Doldu';
                        }

                        return 'Yayinda';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pasif' => 'gray',
                        'Yaklasiyor' => 'warning',
                        'Suresi Doldu' => 'danger',
                        default => 'success',
                    }),

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

    /**
     * Paket türüne göre izin verilen duyuru sayısını döndür
     */
    public static function getPackageLimits(): array
    {
        return [
            'baslangic' => 1,
            'profesyonel' => 5,
            'kurumsal' => 999999, // Unlimited
        ];
    }

    /**
     * Mevcut tenant'ın bu pakette kaç duyuru oluşturabileceğini kontrol et
     */
    public static function getAnnouncementLimit(): int
    {
        $tenant = tenant();
        $limits = self::getPackageLimits();
        return $limits[$tenant->package] ?? 1;
    }

    /**
     * Tenant'ın yeni duyuru oluşturup oluşturamayacağını kontrol et
     */
    public static function canCreateMore(): bool
    {
        $limit = self::getAnnouncementLimit();
        if ($limit >= 999999) {
            return true; // Unlimited
        }
        
        $currentCount = Announcement::count();
        return $currentCount < $limit;
    }
}
