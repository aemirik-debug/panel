<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Event extends Model
{
    use HasFactory, TenantConnection;

    protected $fillable = [
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
        'is_active',
    ];
}