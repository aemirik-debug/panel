<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class TextSlider extends Model
{
    use HasFactory, TenantConnection;

    protected $fillable = [
        'page', 
        'title', 
        'subtitle', 
        'image_path', 
        'button_text', 
        'button_link', 
        'is_active', 
        'sort_order'
    ];
}