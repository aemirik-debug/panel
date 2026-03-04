<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextSlider extends Model
{
    use HasFactory;

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