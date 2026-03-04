<?php

namespace App\Filament\Admin\Resources\Pages;

use App\Filament\Admin\Resources\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
}
