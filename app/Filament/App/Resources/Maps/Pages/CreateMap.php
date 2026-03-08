<?php

namespace App\Filament\App\Resources\Maps\Pages;

use App\Filament\App\Resources\Maps\MapResource;
use App\Models\Map;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateMap extends CreateRecord
{
    protected static string $resource = MapResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $iframeCode = $data['iframe_code'] ?? '';

        if (! preg_match('/^\s*<iframe\b[^>]*>.*<\/iframe>\s*$/is', $iframeCode)) {
            Notification::make()
                ->danger()
                ->title('Geçersiz harita kodu')
                ->body('Lütfen sadece geçerli bir iframe kodu girin.')
                ->persistent()
                ->send();

            $this->halt();
        }

        $page = $data['page'] ?? null;
        
        if ($page === 'footer') {
            $existingCount = Map::where('page', 'footer')->count();
            
            if ($existingCount >= 1) {
                Notification::make()
                    ->danger()
                    ->title('Footer için zaten bir harita eklenmiş')
                    ->body('Lütfen mevcut haritayı düzenleyin veya silin.')
                    ->persistent()
                    ->send();
                
                $this->halt();
            }
        }
        
        if ($page === 'iletisim') {
            $existingCount = Map::where('page', 'iletisim')->count();
            
            if ($existingCount >= 6) {
                Notification::make()
                    ->danger()
                    ->title('Maksimum harita sayısına ulaşıldı')
                    ->body('İletişim sayfası için maksimum 6 harita eklenebilir.')
                    ->persistent()
                    ->send();
                
                $this->halt();
            }
        }

        return $data;
    }
}
