<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Product extends Model
{
    use HasFactory, TenantConnection;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'main_image',
        'gallery_images',
        'price',
        'old_price',
        'sku',
        'stock',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->orderByDesc('created_at');
    }
}
