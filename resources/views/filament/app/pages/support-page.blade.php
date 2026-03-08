@php
    use Filament\Support\Enums\MaxWidth;
@endphp

<x-filament-panels::page>
    <div class="grid gap-6">
        <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
            <h3 class="font-semibold text-blue-900 mb-2">💡 Destek Hakkında</h3>
            <p class="text-sm text-blue-800">
                Herhangi bir sorunla karşılaşırsanız veya geri bildirim vermek istiyorsanız, aşağıdaki formu doldurarak bize ulaşabilirsiniz. 
                Ekran görüntüsü eklemek, sorunun çözülmesini hızlandırabilir.
            </p>
        </div>

        {{ $this->form }}

        <div class="flex gap-3">
            @foreach ($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
