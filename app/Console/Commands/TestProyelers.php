<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestProyelers extends Command
{
    protected $signature = 'test:proyelers';
    protected $description = 'Test projeler and referanslar routes';

    public function handle()
    {
        $this->line('=== /projeler Route Testi ===');
        
        // Portfolio modelini test et
        try {
            $portfolios = \App\Models\Portfolio::where('is_active', true)->orderBy('order', 'asc')->get();
            $this->info("✅ Portfolio query çalıştı: " . count($portfolios) . " record");
        } catch (\Exception $e) {
            $this->error("❌ Portfolio query hatası: " . $e->getMessage());
        }

        // Settings test et
        try {
            $settings = \App\Models\Setting::first();
            $this->info("✅ Settings query çalıştı");
        } catch (\Exception $e) {
            $this->error("❌ Settings query hatası: " . $e->getMessage());
        }

        // View render test et
        try {
            $theme = 'theme_1';
            $view = view("themes.{$theme}.pages.projeler", compact('portfolios', 'settings'));
            $html = $view->render();
            $this->info("✅ View render başarılı: " . strlen($html) . " bytes");
        } catch (\Exception $e) {
            $this->error("❌ View render hatası: " . $e->getMessage());
        }

        // Routes'ı kontrol et
        $this->line("\n=== Routes Kontrol ===");
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $projelerFound = false;
        $referanslarFound = false;

        foreach ($routes as $route) {
            $uri = $route->uri;
            if (strpos($uri, 'projeler') !== false) {
                $projelerFound = true;
                $this->info("✅ /projeler route bulundu");
                $this->line("   URI: " . $uri);
                $this->line("   Methods: " . implode(',', $route->methods));
            }
            if (strpos($uri, 'test-route-123') !== false) {
                $this->info("✅ /test-route-123 route bulundu");
                $this->line("   URI: " . $uri);
            }
            if (strpos($uri, 'referanslar') !== false) {
                $referanslarFound = true;
                $this->info("✅ /referanslar route bulundu");
                $this->line("   URI: " . $uri);
                $this->line("   Methods: " . implode(',', $route->methods));
            }
        }

        if (!$projelerFound) {
            $this->error("❌ /projeler route BULUNAMADI!");
        }
        if (!$referanslarFound) {
            $this->error("❌ /referanslar route BULUNAMADI!");
        }

        // Tenant bilgisi
        $this->line("\n=== Tenant Bilgisi ===");
        try {
            $tenant = \Stancl\Tenancy\Tenancy::getInstance()->current();
            if ($tenant) {
                $this->info("✅ Aktif Tenant: " . $tenant->id);
                $this->line("   Domain: " . $tenant->domain);
                $this->line("   Theme: " . ($tenant->theme ?? 'theme_1'));
            }
        } catch (\Exception $e) {
            $this->warn("⚠️ Tenant context hatası: " . $e->getMessage());
        }
    }
}
