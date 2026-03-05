<?php

namespace App\Filament\Traits;

use App\Models\Tenant;
use Illuminate\Support\Facades\Cache;

trait HasPackageModule
{
    /**
     * Navigation'da gösterilmesi gerekiyor mu?
     * Tenant'ın paketine göre kontrol eder
     */
    public static function shouldRegisterNavigation(): bool
    {
        // Eğer packageModule tanımlı değilse, her zaman göster
        if (!isset(static::$packageModule) || static::$packageModule === null) {
            return true;
        }

        // Tenant'ı al
        $tenant = tenant();
        
        if (!$tenant) {
            return false;
        }

        // Tenant'ın paketine göre aktif modülleri al
        $activeModules = $tenant->modules ?? Tenant::getPackageModules($tenant->package ?? 'baslangic');

        // Bu modül aktif mi?
        return in_array(static::$packageModule, $activeModules);
    }

    /**
     * Kullanıcı bu resource'a erişebilir mi?
     */
    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation() && parent::canAccess();
    }
}
