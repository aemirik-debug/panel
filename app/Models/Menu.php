<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Menu extends Model
{
    use HasFactory, TenantConnection;

    protected $fillable = [
        'title',
        'menu_type',
        'url',
        'slug',
        'description',
        'icon',
        'order',
        'parent_id',
        'page_id',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function routeByMenuType(string $menuType): ?string
    {
        return match ($menuType) {
            'home' => '/',
            'about' => '/hakkimizda',
            'services' => '/hizmetler',
            'references' => '/referanslar',
            'portfolio' => '/projeler',
            'blog' => '/blog',
            'contact' => '/iletisim',
            default => null,
        };
    }

    // Üst menüyü getir
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Alt menüleri getir (çocuklar)
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order', 'asc');
    }

    // Bağlantılı sayfa
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    // Slug otomatik oluşturma (saving event'de)
    protected static function booted()
    {
        static::saving(function ($menu) {
            if (empty($menu->slug) || $menu->isDirty('title')) {
                $menu->slug = Str::slug($menu->title);
            }

            if (filled($menu->menu_type) && $menu->menu_type !== 'custom_url' && $menu->menu_type !== 'custom_page') {
                $mappedRoute = static::routeByMenuType($menu->menu_type);

                if (filled($mappedRoute)) {
                    $menu->url = $mappedRoute;
                }
            }

            if (blank($menu->url) && empty($menu->page_id)) {
                $routeBySlug = static::routeByMenuType($menu->slug);

                if (filled($routeBySlug)) {
                    $menu->url = $routeBySlug;
                }
            }
            
            if (empty($menu->meta_title)) {
                $menu->meta_title = $menu->title;
            }
        });
    }
}