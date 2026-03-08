<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Map extends Model
{
    use HasFactory, TenantConnection;

    protected $fillable = ['page', 'title', 'iframe_code', 'is_active'];
}