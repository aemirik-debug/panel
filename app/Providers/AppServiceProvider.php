<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Setting;
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
        // Frontend tema görünümlerine ortak verileri tek noktadan enjekte et.
        View::composer('themes.*', function ($view) {
            try {
                $settings = null;
                $menus = collect();

                if (Schema::hasTable('settings')) {
                    $settings = Setting::first();
                }

                if (Schema::hasTable('menus')) {
                    $query = Menu::query()
                        ->where('is_active', true)
                        ->orderBy('order');

                    if (Schema::hasColumn('menus', 'parent_id')) {
                        $menus = $query
                            ->whereNull('parent_id')
                            ->with([
                                'page',
                                'children' => fn ($childQuery) => $childQuery
                                    ->where('is_active', true)
                                    ->orderBy('order')
                                    ->with('page'),
                            ])
                            ->get();
                    } else {
                        $menus = $query
                            ->with('page')
                            ->get()
                            ->map(function (Menu $menu) {
                                $menu->setRelation('children', collect());

                                return $menu;
                            });
                    }
                }

                $view->with('settings', $settings);
                $view->with('menus', $menus);
            } catch (\Throwable $e) {
                // Tema render ederken migration öncesi hatalarda sayfayı kırma.
                $view->with('settings', null);
                $view->with('menus', collect());
            }
        });
    }
}