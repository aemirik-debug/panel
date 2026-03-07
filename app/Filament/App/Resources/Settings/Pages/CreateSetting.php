<?php

namespace App\Filament\App\Resources\Settings\Pages;

use App\Filament\App\Resources\Settings\SettingResource;
use App\Models\Setting;
use Filament\Resources\Pages\CreateRecord;

class CreateSetting extends CreateRecord
{
    protected static string $resource = SettingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['home_sections'] = Setting::normalizeHomeSections($data['home_sections'] ?? null);

        return $data;
    }
}
