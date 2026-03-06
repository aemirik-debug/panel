<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Category extends Model
{
    use TenantConnection;
    
    protected $fillable = [
        'name', 
        'slug', 
        'parent_id',
        'type', 
        'description', 
        'image', 
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('name');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }
}
