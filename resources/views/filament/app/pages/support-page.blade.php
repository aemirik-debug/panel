@php
    $tickets = $this->getTickets();
    $hasNewReplies = count($this->newlyRepliedIds) > 0;
    $catLabels = [
        'blog' => 'Blog', 'products' => 'Ürünler', 'services' => 'Hizmetler',
        'categories' => 'Kategoriler', 'gallery' => 'Galeri', 'slider' => 'Slider',
        'menu' => 'Menüler', 'settings' => 'Ayarlar', 'other' => 'Diğer',
    ];
@endphp

<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Yeni yanıt bildirimi --}}
        @if($hasNewReplies)
        <div class="flex items-center gap-4 rounded-xl bg-emerald-50 border border-emerald-200 px-5 py-4">
            <div class="shrink-0 w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="w-5 h-5 text-emerald-600" />
            </div>
            <div>
                <p class="font-semibold text-emerald-900">
                    {{ count($this->newlyRepliedIds) === 1 ? '1 talebinize' : count($this->newlyRepliedIds).' talebinize' }} yeni yanıt geldi!
                </p>
                <p class="text-sm text-emerald-700">Aşağıda yeşil çerçeveli taleplerde yanıtları görebilirsiniz.</p>
            </div>
        </div>
        @endif

        {{-- Talep listesi --}}
        <div>
            <h2 class="text-base font-semibold text-gray-900 mb-3">Taleplerim</h2>

            @if($tickets->isEmpty())
            <div class="rounded-xl border-2 border-dashed border-gray-200 py-10 px-6 text-center">
                <x-filament::icon icon="heroicon-o-inbox" class="w-10 h-10 text-gray-300 mx-auto mb-3" />
                <p class="font-medium text-gray-500">Henüz destek talebi oluşturmadınız.</p>
                <p class="text-sm text-gray-400 mt-1">Aşağıdan yeni bir talep açabilirsiniz.</p>
            </div>
            @else
            <div class="space-y-3">
                @foreach($tickets as $ticket)
                @php
                    $isNew     = in_array($ticket->id, $this->newlyRepliedIds);
                    $statusCss = \App\Models\SupportTicket::getStatusBadgeClass($ticket->status);
                    $statusLabel = \App\Models\SupportTicket::getStatusLabel($ticket->status);
                @endphp
                <div class="rounded-xl border {{ $isNew ? 'border-emerald-300 ring-1 ring-emerald-200' : 'border-gray-200' }} bg-white shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $ticket->title }}</h3>
                                    @if($isNew)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                        ✦ Yeni Yanıt
                                    </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $ticket->created_at->format('d.m.Y H:i') }}
                                    &nbsp;·&nbsp;
                                    {{ $catLabels[$ticket->category] ?? $ticket->category }}
                                </p>
                            </div>
                            <span class="shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusCss }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>

                    @if($ticket->admin_reply)
                    <div class="border-t {{ $isNew ? 'border-emerald-200 bg-emerald-50' : 'border-blue-100 bg-blue-50' }} px-4 py-3">
                        <p class="text-xs font-semibold {{ $isNew ? 'text-emerald-700' : 'text-blue-600' }} mb-1.5 flex items-center gap-1.5">
                            <x-filament::icon icon="heroicon-o-chat-bubble-left" class="w-3.5 h-3.5" />
                            Destek Ekibi Yanıtı
                        </p>
                        <p class="text-sm {{ $isNew ? 'text-emerald-900' : 'text-blue-900' }} leading-relaxed whitespace-pre-wrap">{{ $ticket->admin_reply }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Yeni talep formu (akordiyon) --}}
        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-sm">
            <button
                wire:click="toggleForm"
                type="button"
                class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors text-left"
            >
                <span class="flex items-center gap-2 font-semibold text-gray-800">
                    <x-filament::icon icon="heroicon-o-plus-circle" class="w-5 h-5 text-primary-600" />
                    Yeni Destek Talebi Oluştur
                </span>
                @if($showForm)
                    <x-filament::icon icon="heroicon-o-chevron-up" class="w-4 h-4 text-gray-400" />
                @else
                    <x-filament::icon icon="heroicon-o-chevron-down" class="w-4 h-4 text-gray-400" />
                @endif
            </button>

            @if($showForm)
            <div class="border-t border-gray-200 bg-gray-50 p-5">
                <div class="mb-4 rounded-lg bg-blue-50 border border-blue-200 px-4 py-3">
                    <p class="text-sm text-blue-800">
                        💡 Sorununuzu detaylı anlatın. Varsa ekran görüntüsü eklemeniz çözüm sürecini hızlandırır.
                    </p>
                </div>

                {{ $this->form }}

                <div class="mt-4 flex gap-3">
                    @foreach ($this->getFormActions() as $action)
                        {{ $action }}
                    @endforeach
                </div>
            </div>
            @endif
        </div>

    </div>
</x-filament-panels::page>
