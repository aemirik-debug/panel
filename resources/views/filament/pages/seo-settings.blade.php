<x-filament-panels::page>
    <form wire:submit="save">
        
        {{ $this->form }}

        <div style="margin-top: 2.5rem;">
            <x-filament::button type="submit">
                Değişiklikleri Kaydet
            </x-filament::button>
        </div>
        
    </form>

    <div style="margin-top: 3rem; padding: 1.5rem; background-color: rgba(255, 255, 255, 0.05); border-radius: 0.5rem; border: 1px solid rgba(255, 255, 255, 0.1);">
        <h3 style="font-size: 1.2rem; font-weight: bold; margin-bottom: 1rem; color: #fff;">Site Haritası (Sitemap) Yönetimi</h3>
        
        <p style="margin-bottom: 1.5rem; color: #a1a1aa; font-size: 0.9rem;">
            Arama motorları için sitenizin sitemap.xml dosyasını buradan oluşturabilir veya silebilirsiniz.
        </p>

        @if($sitemapExists)
            <div style="display: flex; align-items: center; gap: 1rem;">
                <span style="color: #10b981; font-weight: bold;">✓ Durum: Mevcut (Yayında)</span>
                <x-filament::button wire:click="deleteSitemap" color="danger">
                    Site Haritasını Sil
                </x-filament::button>
            </div>
        @else
            <div style="display: flex; align-items: center; gap: 1rem;">
                <span style="color: #ef4444; font-weight: bold;">✗ Durum: Bulunamadı (Yok)</span>
                <x-filament::button wire:click="generateSitemap" color="success">
                    Site Haritası Oluştur
                </x-filament::button>
            </div>
        @endif
    </div>

</x-filament-panels::page>