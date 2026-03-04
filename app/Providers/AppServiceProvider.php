<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\Menu;
use App\Models\Slider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        // Proje terminal üzerinden (migrate vs.) çalışmıyorsa devreye gir
        if (!app()->runningInConsole()) {
            try {
                // Sadece tablolar varsa veriyi çek
                if (Schema::hasTable('sliders')) {
                    View::share('sliders', Slider::where('is_active', true)->orderBy('order', 'asc')->get());
                }

                if (Schema::hasTable('settings')) {
                    View::share('settings', Setting::first());
                }

                if (Schema::hasTable('menus')) {
                    View::share('menus', Menu::where('is_active', true)->orderBy('order')->get());
                }
            } catch (\Exception $e) {
                // Veritabanı veya tablo henüz hazır değilse hata vermemesi için boş bırakıyoruz
            }
        }
    }
}