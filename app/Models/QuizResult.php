<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class QuizResult extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'quiz_title',
        'user_name',
        'user_email',
        'user_phone',
        'details',
    ];

    // JSON formatındaki sonuç detaylarını Laravel'in otomatik diziye çevirmesi için
    protected $casts = [
        'details' => 'array',
    ];
}