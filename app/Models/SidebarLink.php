<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SidebarLink extends Model
{
    use HasFactory;
    protected $fillable = ['page', 'link_title', 'url', 'sort_order', 'is_active'];
}