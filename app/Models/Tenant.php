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
                'services',      // Hizmetler
                'contacts',      // İletişim Formları
                'maps',          // Haritalar
                'galleries',     // Galeriler
                'sliders',       // Slider
                'settings',      // Site Ayarları
                'menus',         // Menü Yönetimi
                'pages',         // Özel Sayfalar
                'comments',      // Referanslar
                'portfolios',    // Projeler
            ],
            'profesyonel' => [
                'services',
                'contacts',
                'maps',
                'galleries',
                'sliders',
                'settings',
                'menus',
                'pages',
                'posts',         // Blog/İçerik
                'categories',    // Kategoriler
                'comments',      // Yorumlar
                'portfolios',    // Projeler
                'sidebar_links', // Sidebar Linkleri
            ],
            'kurumsal' => [
                'services',
                'contacts',
                'galleries',
                'sliders',
                'settings',
                'menus',
                'pages',
                'posts',
                'categories',
                'comments',
                'portfolios',    // Projeler
                'sidebar_links',
                'events',        // Etkinlikler
                'quizzes',       // Anketler
                'quiz_results',  // Anket Sonuçları
                'music',         // Müzik/Video
                'maps',          // Haritalar
                'text_sliders',  // Text Sliderlar
                'modal_settings',// Modal Ayarları
                'users',         // Kullanıcı Yönetimi
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
            'profesyonel' => '📦 PROFESYONEL PAKETİ',
            'kurumsal' => '📦 KURUMSAL PAKETİ',
            default => 'Bilinmeyen Paket',
        };
    }
    
public function domains(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(\Stancl\Tenancy\Database\Models\Domain::class);
}
}