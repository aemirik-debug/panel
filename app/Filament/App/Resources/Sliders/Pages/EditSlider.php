<?php

namespace App\Filament\App\Resources\Sliders\Pages;

use App\Filament\App\Resources\Sliders\SliderResource;
use App\Models\Slider;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditSlider extends EditRecord
{
    protected static string $resource = SliderResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['is_active'] ?? false) === true) {
            $hasAnotherActiveSlider = Slider::query()
                ->where('is_active', true)
                ->whereKeyNot($this->record->getKey())
                ->exists();

            if ($hasAnotherActiveSlider) {
                throw ValidationException::withMessages([
                    'is_active' => 'Sadece bir adet slide modeli secilebilir ve aktif olabilir. Once diger aktif modeli pasife alin.',
                ]);
            }
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

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
