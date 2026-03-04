<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class SeoSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.seo-settings';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationLabel = 'SEO OPTİMİZASYONU';
    protected static string|\UnitEnum|null $navigationGroup = 'SİTE YÖNETİMİ';

    public ?array $data = [];
    
    // Sitemap dosyasının var olup olmadığını tutan değişken
    public bool $sitemapExists = false; 

    public function mount(): void
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        $this->form->fill($setting->toArray());
        
        // Sayfa açıldığında public klasöründe sitemap.xml var mı kontrol eder
        $this->sitemapExists = file_exists(public_path('sitemap.xml'));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('SEO ve Genel Ayarlar')
                    ->schema([
                        TextInput::make('meta_title')->label('Site Başlığı (Title)'),
                        Textarea::make('meta_description')->label('Site Tanıtım Cümlesi (Desc)'),
                        TextInput::make('site_keywords')->label('Site Anahtar Kelimeleri'),
                        TextInput::make('site_name')->label('Site Adı'),
                        TextInput::make('twitter')->label('Twitter Kullanıcı Adı')->prefix('@'),
                        Textarea::make('google_analytics')->label('Google Analytics Kodu')->rows(5),
                        FileUpload::make('favicon')->label('Favicon Seçiniz')->image()->directory('settings'),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Değişiklikleri Kaydet')
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        $setting->update($this->form->getState());

        Notification::make()
            ->title('Başarılı')
            ->body('Ayarlar kaydedildi.')
            ->success()
            ->send();
    }

    // Sitemap Oluşturma Fonksiyonu
    public function generateSitemap(): void
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <url>
      <loc>' . url('/') . '</loc>
      <lastmod>' . now()->toAtomString() . '</lastmod>
   </url>
</urlset>';
        
        file_put_contents(public_path('sitemap.xml'), $content);
        $this->sitemapExists = true;

        Notification::make()
            ->title('Başarılı')
            ->body('Site Haritası (sitemap.xml) oluşturuldu.')
            ->success()
            ->send();
    }

    // Sitemap Silme Fonksiyonu
    public function deleteSitemap(): void
    {
        if (file_exists(public_path('sitemap.xml'))) {
            unlink(public_path('sitemap.xml'));
        }
        $this->sitemapExists = false;

        Notification::make()
            ->title('Başarılı')
            ->body('Site Haritası silindi.')
            ->success()
            ->send();
    }
}