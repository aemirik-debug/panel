<?php

namespace App\Filament\App\Resources\Settings\Pages;

use App\Filament\App\Resources\Settings\SettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Models\Setting;


class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
	public function mount(): void
{
    $setting = Setting::first();

    if ($setting) {
        $this->redirect(static::getResource()::getUrl('edit', ['record' => $setting]));
    } else {
        $this->redirect(static::getResource()::getUrl('create'));
    }
}
    public function getSubheading(): ?string
    {
        return 'Bu alanda ilgili kayitlari yonetebilirsiniz.';
    }
}

