<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Setting;
use App\Models\Domain;
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
        // Frontend görünümlerine ortak verileri tek noktadan enjekte et.
        View::composer(['themes.*', 'layouts.*', 'partials.*'], function ($view) {
            try {
                $settings = null;
                $menus = collect();
                $footerMap = null;
                $footerCreditText = null;

                if (Schema::hasTable('settings')) {
                    $settings = Setting::first();
                }

                if (Schema::hasTable('maps')) {
                    $footerMap = \App\Models\Map::query()
                        ->where('is_active', true)
                        ->where('page', 'footer')
                        ->first();
                }

                if (Schema::hasTable('domains') && Schema::hasTable('tenants') && Schema::hasColumn('tenants', 'footer_credit_text')) {
                    $currentHost = request()->getHost();
                    $domain = Domain::query()
                        ->where('domain', $currentHost)
                        ->with('tenant')
                        ->first();

                    if ($domain && $domain->tenant) {
                        $footerCreditText = $domain->tenant->footer_credit_text;
                    }
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
                $view->with('footerMap', $footerMap);
                $view->with('footerCreditText', $footerCreditText);
            } catch (\Throwable $e) {
                // Tema render ederken migration öncesi hatalarda sayfayı kırma.
                $view->with('settings', null);
                $view->with('menus', collect());
                $view->with('footerMap', null);
                $view->with('footerCreditText', null);
            }
        });
    }
}