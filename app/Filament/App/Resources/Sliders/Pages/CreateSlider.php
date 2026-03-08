<?php

namespace App\Filament\App\Resources\Sliders\Pages;

use App\Filament\App\Resources\Sliders\SliderResource;
use App\Models\Slider;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateSlider extends CreateRecord
{
    protected static string $resource = SliderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $hasActiveSlider = Slider::query()
            ->where('is_active', true)
            ->exists();

        if ($hasActiveSlider) {
            throw ValidationException::withMessages([
                'slider_model' => 'Sistemde aktif bir slide modeli var. Yeni model secmek icin once aktif modeli pasife alin.',
            ]);
        }

        // Map first slide to legacy columns for NOT NULL constraint and table compatibility
        $firstSlide = collect($data['slides'] ?? [])->first();
        if ($firstSlide) {
            $data['image'] = $firstSlide['image'] ?? null;
            $data['title'] = $firstSlide['title'] ?? null;
            $data['subtitle'] = $firstSlide['subtitle'] ?? null;
            $data['button_text'] = $firstSlide['button_text'] ?? null;
            $data['button_url'] = $firstSlide['button_url'] ?? null;
        }

        return $data;
    }
}
