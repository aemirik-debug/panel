<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Comment extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'name_surname',
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
