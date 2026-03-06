<?php

namespace App\Filament\App\Resources\Contacts\Pages;

use App\Filament\App\Resources\Contacts\ContactResource;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Efsanevi Excel'e Aktar Butonumuz
            ExportAction::make()
                ->label('Excel\'e Aktar')
                ->color('primary')
                ->icon('heroicon-o-document-arrow-down')
                ->exports([
                    ExcelExport::make()
                        ->fromTable() // Tabloda hangi sÃ¼tunlar varsa otomatik onlarÄ± alÄ±r
                        ->withFilename('form_kayitlari_' . date('Y_m_d')) // Ä°ndirilen dosyanÄ±n adÄ±
                ]),
        ];
    }
    public function getSubheading(): ?string
    {
        return 'Bu alanda ilgili kayitlari yonetebilirsiniz.';
    }
}

