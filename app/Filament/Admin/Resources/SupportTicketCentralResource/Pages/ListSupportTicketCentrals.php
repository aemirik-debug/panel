<?php

namespace App\Filament\Admin\Resources\SupportTicketCentralResource\Pages;

use App\Filament\Admin\Resources\SupportTicketCentralResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupportTicketCentrals extends ListRecords
{
    protected static string $resource = SupportTicketCentralResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
