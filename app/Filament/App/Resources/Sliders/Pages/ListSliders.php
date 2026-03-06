<?php

namespace App\Filament\App\Resources\Sliders\Pages;

use App\Filament\App\Resources\Sliders\SliderResource;
use App\Models\Slider;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSliders extends ListRecords
{
    protected static string $resource = SliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Yeni Slider Modeli')
                ->disabled(fn (): bool => Slider::query()->where('is_active', true)->exists())
                ->tooltip(fn (): ?string => Slider::query()->where('is_active', true)->exists()
                    ? 'Yeni model secmek icin once aktif modeli pasife alin.'
                    : null),
        ];
    }
    public function getSubheading(): ?string
    {
        return 'Bu alanda ilgili kayitlari yonetebilirsiniz.';
    }
}

