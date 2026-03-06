<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Slider extends Model
{
    use TenantConnection;
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'slider_model',
        'button_text',
        'button_url',
        'left_caption',
        'right_top_image',
        'right_top_caption',
        'right_bottom_image',
        'right_bottom_caption',
        'is_active',
        'sort',
        'order',
        'slides', // JSON array of slides (max 10)
    ];

    protected $casts = [
        'slides' => 'array',
    ];
}
