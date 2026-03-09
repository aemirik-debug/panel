<?php

namespace App\Filament\App\Resources\SocialMedia;

use App\Filament\App\Resources\SocialMedia\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\SocialMedia;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class SocialMediaResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'social_media';

    protected static ?string $model = SocialMedia::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShare;

    public static function getNavigationLabel(): string
    {
        return 'Sosyal Medya';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'İletişim & Etkileşim';
    }

    protected static ?int $navigationSort = 130;

    public static function getPluralLabel(): string
    {
        return 'Sosyal Medya';
    }

    public static function getLabel(): string
    {
        return 'Sosyal Medya';
    }

    // 🔥 TEK KAYIT OLSUN
    public static function canCreate(): bool
    {
        return ! SocialMedia::exists();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Sosyal Medya Hesapları')
                    ->description('Sosyal medya hesap linklerinizi buradan yönetebilirsiniz.')
                    ->schema([

                        Grid::make(2)->schema([

                            TextInput::make('facebook')
                                ->label('Facebook')
                                ->url()
                                ->placeholder('https://facebook.com/sirketiniz')
                                ->helperText('Facebook profil veya sayfa linkinizi girin'),

                            TextInput::make('instagram')
                                ->label('Instagram')
                                ->url()
                                ->placeholder('https://instagram.com/sirketiniz')
                                ->helperText('Instagram profil linkinizi girin'),

                            TextInput::make('twitter')
                                ->label('Twitter / X')
                                ->url()
                                ->placeholder('https://twitter.com/sirketiniz')
                                ->helperText('Twitter/X profil linkinizi girin'),

                            TextInput::make('linkedin')
                                ->label('LinkedIn')
                                ->url()
                                ->placeholder('https://linkedin.com/company/sirketiniz')
                                ->helperText('LinkedIn şirket sayfanızın linkini girin'),

                        ]),
                    ]),

                Section::make('WhatsApp Ayarları')
                    ->description('WhatsApp iletişim ayarlarınızı buradan düzenleyebilirsiniz.')
                    ->schema([

                        TextInput::make('whatsapp_number')
                            ->label('WhatsApp Numarası')
                            ->helperText('Örn: 905001234567 (ülke kodu ile birlikte, başında + olmadan)')
                            ->placeholder('905001234567')
                            ->tel(),

                        Textarea::make('whatsapp_message')
                            ->label('Varsayılan Mesaj')
                            ->helperText('Kullanıcılar WhatsApp\'tan yazarken bu mesaj otomatik gelecektir')
                            ->placeholder('Merhaba, size nasıl yardımcı olabilirim?')
                            ->rows(3)
                            ->default('Merhaba, size nasıl yardımcı olabilirim?'),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('facebook')
                    ->label('Facebook')
                    ->limit(30)
                    ->url(fn($record) => $record->facebook)
                    ->openUrlInNewTab()
                    ->placeholder('Belirtilmedi'),

                TextColumn::make('instagram')
                    ->label('Instagram')
                    ->limit(30)
                    ->url(fn($record) => $record->instagram)
                    ->openUrlInNewTab()
                    ->placeholder('Belirtilmedi'),

                TextColumn::make('twitter')
                    ->label('Twitter/X')
                    ->limit(30)
                    ->url(fn($record) => $record->twitter)
                    ->openUrlInNewTab()
                    ->placeholder('Belirtilmedi'),

                TextColumn::make('whatsapp_number')
                    ->label('WhatsApp')
                    ->formatStateUsing(fn($state) => $state ? '+' . $state : 'Belirtilmedi')
                    ->placeholder('Belirtilmedi'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->emptyStateHeading('Sosyal Medya Ayarları Yok')
            ->emptyStateDescription('Sosyal medya ve WhatsApp bilgilerinizi eklemek için "Yeni" butonuna tıklayın.')
            ->emptyStateIcon('heroicon-o-share');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocialMedia::route('/'),
            'create' => Pages\CreateSocialMedia::route('/create'),
            'edit' => Pages\EditSocialMedia::route('/{record}/edit'),
        ];
    }
}
