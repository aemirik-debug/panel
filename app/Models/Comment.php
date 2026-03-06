<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Comment extends Model
{
    use TenantConnection;
    
    protected $fillable = [
        'name_surname',
        'image',
        'position',
        'comment',
        'is_active',
    ];
    
    // Accessor for name
    public function getNameAttribute()
    {
        return $this->name_surname;
    }
    
    // Accessor for content
    public function getContentAttribute()
    {
        return $this->comment;
    }
}
