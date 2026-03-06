<?php

namespace App\Filament\App\Resources\QuizResults;

use App\Filament\App\Resources\QuizResults\Pages;
use App\Filament\Traits\HasPackageModule;
use App\Models\QuizResult;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

// İsimlendirme hatası almamak için sisteminin dilinden anlayan buton yolları
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class QuizResultResource extends Resource
{
    use HasPackageModule;

    protected static ?string $packageModule = 'quiz_results';
    protected static ?string $model = QuizResult::class;

    // Karneleri temsil etmesi için şık bir sonuç/belge ikonu
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    public static function getNavigationLabel(): string
    {
        return 'Sınav Sonuçları';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Diğer Özellikler';
    }

    protected static ?int $navigationSort = 175;

    public static function getPluralLabel(): string
    {
        return 'Sınav Sonuçları';
    }

    public static function form(Schema $schema): Schema
    {
        // Sınav sonuçları siteden otomatik geleceği için panelden elle veri girmeyeceğiz, form boş kalacak.
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

                TextColumn::make('quiz_title')
                    ->label('Sınav Adı')
                    ->sortable()
                    ->searchable(),

                // ESKİ SİSTEMDEKİ "BİLGİLER" SÜTUNU (Ad, Email, Telefon tek hücrede)
                TextColumn::make('user_info')
                    ->label('Bilgiler')
                    ->html()
                    ->getStateUsing(function ($record) {
                        return new HtmlString("
                            <b>Ad Soyad:</b> {$record->user_name} <br>
                            <b>Email Adresi:</b> <a href='mailto:{$record->user_email}' style='color:blue;'>{$record->user_email}</a> <br>
                            <b>Telefon:</b> {$record->user_phone}
                        ");
                    }),

                // ESKİ SİSTEMDEKİ "SINAV DETAYLARI" SÜTUNU (Sorular ve Cevaplar tek hücrede)
                TextColumn::make('details')
                    ->label('Sınav Detayları')
                    ->html()
                    ->getStateUsing(function ($record) {
                        // Eğer detay yoksa boş dönsün
                        if (!$record->details) return '-';

                        // Varsa JSON verisini döngüye sokup şık bir HTML formatına çeviriyoruz
                        $html = '<div style="max-height: 200px; overflow-y: auto; padding-right: 10px;">';
                        $html .= '<b style="color:red;">SORULAR:</b><br><br>';
                        
                        foreach($record->details as $item) {
                            $question = $item['question'] ?? '-';
                            $correct = $item['correct'] ?? '-';
                            $given = $item['given'] ?? '-';
                            
                            // Kullanıcı doğru bildiyse yeşil, yanlış bildiyse kırmızı yapalım (şık bir detay)
                            $color = ($correct === $given) ? 'green' : 'red';

                            $html .= "<b>Soru:</b> {$question}<br>";
                            $html .= "<b>Doğru Cevap:</b> {$correct}<br>";
                            $html .= "<b>Verilen Cevap:</b> <span style='color:{$color}; font-weight:bold;'>{$given}</span><br><br>";
                        }
                        
                        $html .= '</div>';
                        return new HtmlString($html);
                    }),

                TextColumn::make('created_at')
                    ->label('Ekleme Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50) // Attığın resimdeki 50 tane göster ayarı
            ->actions([
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
            'index' => Pages\ListQuizResults::route('/'),
        ];
    }
}