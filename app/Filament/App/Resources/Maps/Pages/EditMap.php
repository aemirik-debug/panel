<?php

namespace App\Filament\App\Resources\Maps\Pages;

use App\Filament\App\Resources\Maps\MapResource;
use App\Models\Map;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditMap extends EditRecord
{
    protected static string $resource = MapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
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
        $currentRecordId = $this->record->id;
        
        if ($page === 'footer') {
            $existingCount = Map::where('page', 'footer')
                ->where('id', '!=', $currentRecordId)
                ->count();
            
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
            $existingCount = Map::where('page', 'iletisim')
                ->where('id', '!=', $currentRecordId)
                ->count();
            
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
