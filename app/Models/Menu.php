<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Support\Str;

class Menu extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'title',
        'url',
        'slug',
        'description',
        'icon',
        'order',
        'parent_id',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

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

    // Slug otomatik oluşturma (saving event'de)
    protected static function booted()
    {
        static::saving(function ($menu) {
            if (empty($menu->slug) || $menu->isDirty('title')) {
                $menu->slug = Str::slug($menu->title);
            }
            
            if (empty($menu->meta_title)) {
                $menu->meta_title = $menu->title;
            }
        });
    }
}