<?php

namespace App\Providers;

use App\Http\Middleware\InitializeTenancyForLivewire;
use App\Models\Menu;
use App\Models\Setting;
use App\Models\Domain;
use App\Models\Post;
use App\Models\Service;
use App\Models\Page;
use App\Models\Product;
use App\Observers\PostObserver;
use App\Observers\ServiceObserver;
use App\Observers\PageObserver;
use App\Observers\ProductObserver;
use Illuminate\Database\Connection;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

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
        // Livewire update endpoint'i tenant context içinde çalışmalı,
        // aksi halde CSRF/session eşleşmez ve 419 hatası alınır.
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/livewire/update', $handle)
                ->middleware([
                    'web',
                    InitializeTenancyForLivewire::class,
                ]);
        });

        // Model Observer'ları kaydet (RichEditor görsel optimizasyonu için)
        Post::observe(PostObserver::class);
        Service::observe(ServiceObserver::class);
        Page::observe(PageObserver::class);
        Product::observe(ProductObserver::class);

        if (! app()->runningInConsole()) {
            DB::whenQueryingForLongerThan((int) env('SLOW_DB_TOTAL_MS', 800), function (Connection $connection, QueryExecuted $event): void {
                $request = request();

                if (! $request->is('yonetim*') && ! $request->is('livewire/*')) {
                    return;
                }

                \Log::warning('Slow DB activity detected on panel request', [
                    'connection' => $connection->getName(),
                    'path' => $request->path(),
                    'route' => optional($request->route())->getName(),
                    'last_query_ms' => $event->time,
                    'last_query_sql' => substr($event->sql, 0, 500),
                    'user_id' => optional($request->user())->id,
                    'tenant_id' => function_exists('tenant') && tenant() ? tenant('id') : null,
                ]);
            });
        }

        // Frontend görünümlerine ortak verileri tek noktadan enjekte et.
        // NOT: Filament admin panelinde bu verileri kullanmıyoruz, sadece frontend'te lazım
        View::composer(['themes.*', 'layouts.*', 'partials.*'], function ($view) {
            try {
                // Filament panelinde bu composer'ı çalıştırma
                if (str_contains(request()->route()?->getPrefix() ?? '', 'yonetim')) {
                    return;
                }

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
                        ->orderBy('order')
                        ->with('page');

                    if (Schema::hasColumn('menus', 'parent_id')) {
                        $menus = $query
                            ->whereNull('parent_id')
                            ->with([
                                'children' => fn ($childQuery) => $childQuery
                                    ->where('is_active', true)
                                    ->orderBy('order')
                                    ->with('page'),
                            ])
                            ->get();
                    } else {
                        $menus = $query
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
                \Log::error('View Composer Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                $view->with('settings', null);
                $view->with('menus', collect());
                $view->with('footerMap', null);
                $view->with('footerCreditText', null);
            }
        });

        // Mail ayarlarını settings'ten al ve runtime'da override et
        $this->configureMailFromSettings();
    }

    /**
     * Settings tablosundan mail ayarlarını çek ve Laravel mail config'ini override et.
     */
    protected function configureMailFromSettings(): void
    {
        try {
            if (! app()->runningInConsole() && request()->isMethod('GET') && request()->is('yonetim*')) {
                return;
            }

            // Veritabanı hazır değilse skip et
            if (! Schema::hasTable('settings')) {
                return;
            }

            $settings = Setting::first();

            // Custom mail ayarları aktif değilse .env'deki ayarları kullan
            if (! $settings || ! $settings->use_custom_mail_settings) {
                return;
            }

            // Mail config'ini runtime'da set et
            if (filled($settings->mail_driver)) {
                config(['mail.default' => $settings->mail_driver]);
            }

            if ($settings->mail_driver === 'smtp') {
                config([
                    'mail.mailers.smtp.host' => $settings->mail_host,
                    'mail.mailers.smtp.port' => $settings->mail_port,
                    'mail.mailers.smtp.username' => $settings->mail_username,
                    'mail.mailers.smtp.password' => $settings->mail_password,
                    'mail.mailers.smtp.encryption' => $settings->mail_encryption,
                ]);
            }

            if (filled($settings->mail_from_address)) {
                config([
                    'mail.from.address' => $settings->mail_from_address,
                    'mail.from.name' => $settings->mail_from_name ?? config('app.name'),
                ]);
            }
        } catch (\Throwable $e) {
            // Migration öncesi hata durumunda sessizce geç
            report($e);
        }
    }
}