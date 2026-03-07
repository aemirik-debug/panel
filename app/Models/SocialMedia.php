<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class SocialMedia extends Model
{
    use TenantConnection;
    
    protected $table = 'social_media';
    
    protected $fillable = [
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'whatsapp_number',
        'whatsapp_message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
