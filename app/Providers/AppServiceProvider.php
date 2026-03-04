<?php

namespace App\Providers;
use App\Models\Setting;
use App\Models\Menu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
	// Veritabanındaki aktif sliderları tüm sayfalarda (veya sadece welcome'da) paylaşır
    view()->share('sliders', \App\Models\Slider::where('is_active', true)->orderBy('order', 'asc')->get());
	
	// AppServiceProvider.php içindeki boot metodu
		view()->share('sliders', \App\Models\Slider::where('is_active', true)->orderBy('order')->get());
    // Proje terminal üzerinden (migrate vs.) çalışmıyorsa devreye gir
    if (!app()->runningInConsole()) {
        try {
            // Ayarları paylaş
            \Illuminate\Support\Facades\View::share('settings', \App\Models\Setting::first());
            
            // Menüleri paylaş
            \Illuminate\Support\Facades\View::share('menus', \App\Models\Menu::where('is_active', true)
                ->orderBy('order')
                ->get());
        } catch (\Exception $e) {
            // Veritabanı veya tablo henüz hazır değilse hata verme
        }
    }
}
}
