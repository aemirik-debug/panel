<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Map extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['page', 'title', 'iframe_code', 'is_active'];
}