<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Service extends Model
{
   use HasFactory, TenantConnection;
   
   protected $fillable = [
    'title',
    'slug',
    'short_description',
    'description',
    'icon',
    'image',
    'is_active',
    'order',
    'meta_title',
    'meta_description',
];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
