<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Service extends Model
{
   use HasFactory, BelongsToTenant;
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
