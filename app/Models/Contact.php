<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Contact extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'form_name', // Eklendi
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'note',      // Eklendi
        'is_read',
    ];
}