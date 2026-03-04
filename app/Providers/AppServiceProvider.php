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
       // Sadece bir müşteri (tenant) veritabanı bağlandığında çalış
    if (fn() => isset(tenant()->id)) {
        
        // Verileri sadece frontend (site tarafı) isteklerinde paylaş, 
        // admin panelini yormayalım
        if (!app()->runningInConsole() && !request()->is('admin*')) {
            try {
                if (Schema::hasTable('settings')) {
                    View::share('settings', \App\Models\Setting::first());
                }
                if (Schema::hasTable('menus')) {
                    View::share('menus', \App\Models\Menu::where('is_active', true)->orderBy('order')->get());
                }
                // Slider vb. diğerlerini de buraya ekleyebilirsin
            } catch (\Exception $e) {
                // Hata durumunda sessiz kal
            }
        }
    }
    }
}