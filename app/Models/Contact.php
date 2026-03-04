<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

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