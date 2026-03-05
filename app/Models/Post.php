<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Post extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image_path',
        'is_active',
        'meta_title',
        'meta_description'
    ];
}