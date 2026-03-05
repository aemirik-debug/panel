<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class SidebarLink extends Model
{
    use HasFactory, BelongsToTenant;
    protected $fillable = ['page', 'link_title', 'url', 'sort_order', 'is_active'];
}