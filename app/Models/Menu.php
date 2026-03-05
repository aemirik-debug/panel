<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Menu extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'title',
        'url',
        'order',
        'parent_id', // Üst menü seçimi için eklendi
        'is_active', // Aktif/Pasif durumu için eklendi
    ];

    // Bir menünün üst menüsünü getirecek ilişki (Filament'te çok işimize yarayacak)
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
}