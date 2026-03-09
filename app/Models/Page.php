<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Page extends Model
{
    use HasFactory, TenantConnection;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Page $page): void {
            if (empty($page->slug) || $page->isDirty('title')) {
                $page->slug = Str::slug($page->title);
            }

            if (empty($page->meta_title)) {
                $page->meta_title = $page->title;
            }
        });
    }
}
