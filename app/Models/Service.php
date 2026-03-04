<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
   protected $fillable = [
    'title',
    'slug',
    'short_description',
    'description',
    'icon',
    'image',
    'is_active',
    'order',
];

}
