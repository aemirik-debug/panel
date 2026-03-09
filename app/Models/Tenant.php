<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

protected $casts = [
        'modules' => 'array',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'package',
            'theme',
            'footer_credit_text',
            'modules',
        ];
    }

    /**
     * Paket tanımları ve her pakette aktif olacak modüller
     */
    public static function getPackageModules(string $package): array
    {
        return match($package) {
            'baslangic' => [
                'menus',         // Menu Ayarlari
                'pages',         // Sayfalar
                'services',      // Hizmetler
                'sliders',       // Slider
                'galleries',     // Fotograf Galerisi
                'contacts',      // Form Kayitlari
                'maps',          // Harita
                'social_media',  // Sosyal Medya
                'settings',      // Site Ayarlari
                'users',         // Yoneticiler
            ],
            'kurumsal' => [
                'menus',
                'pages',
                'services',
                'sliders',
                'galleries',
                'contacts',
                'maps',
                'social_media',
                'settings',
                'users',
                'comments',      // Referanslar
                'portfolios',    // Projeler
                'posts',         // Blog
                'announcements', // Duyuru
                'events',        // Etkinlik Takvimi
            ],
            'pro', 'profesyonel' => [
                'menus',
                'pages',
                'services',
                'sliders',
                'galleries',
                'contacts',
                'maps',
                'social_media',
                'settings',
                'users',
                'comments',
                'portfolios',
                'posts',
                'announcements',
                'events',
                'products',      // Urunler
                'categories',    // Urun Kategorileri
            ],
            default => [],
        };
    }

    /**
     * Paket etiketleri
     */
    public static function getPackageLabel(string $package): string
    {
        return match($package) {
            'baslangic' => '📦 BAŞLANGIÇ PAKETİ',
            'kurumsal' => '📦 KURUMSAL PAKETİ',
            'pro', 'profesyonel' => '📦 PRO PAKETİ',
            default => 'Bilinmeyen Paket',
        };
    }
    
public function domains(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(\Stancl\Tenancy\Database\Models\Domain::class);
}
}