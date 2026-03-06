<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Quiz extends Model
{
    use HasFactory, TenantConnection;

    protected $fillable = [
        'page',
        'title',
        'questions',
        'is_active',
    ];

    // JSON formatındaki soruları Laravel'in otomatik diziye (array) çevirmesi için bu ayarı yapıyoruz
    protected $casts = [
        'questions' => 'array',
    ];
}