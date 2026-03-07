<?php

namespace App\Filament\App\Resources\Settings\Pages;

use App\Filament\App\Resources\Settings\SettingResource;
use App\Models\Setting;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['home_sections'] = Setting::normalizeHomeSections($data['home_sections'] ?? null);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['home_sections'] = Setting::normalizeHomeSections($data['home_sections'] ?? null);

        return $data;
    }
}
