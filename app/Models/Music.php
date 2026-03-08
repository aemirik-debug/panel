<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Music extends Model
{
    use HasFactory, TenantConnection;

    protected $table = 'music';

    protected $fillable = [
        'page',
        'title',
        'artist',
        'file_path',
        'is_active',
        'sort_order',
    ];
}