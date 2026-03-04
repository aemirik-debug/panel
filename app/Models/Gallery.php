<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id', // Yeni eklediğimiz sayfa bağı
        'title',
        'image',
        'order',
        'is_active',
    ];

    // Galerinin bağlı olduğu sayfayı (menüyü) getiren ilişki
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}