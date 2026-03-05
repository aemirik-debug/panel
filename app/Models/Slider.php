<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Slider extends Model
{
    use BelongsToTenant;
    protected $fillable = ['title', 'subtitle', 'image', 'button_text', 'button_url', 'is_active', 'sort', 'order'];
}
