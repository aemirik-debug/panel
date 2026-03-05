<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Setting extends Model
{
    use BelongsToTenant;
    // Formdan gelecek tüm alanları buraya yazıyoruz
    protected $fillable = [
        'site_name',
        'phone',
        'email',
        'address',
        'logo',
        'favicon',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'footer_text',
        'meta_title',        // Senin istediğin "Site Başlığı (Title)"
        'meta_description',  // Senin istediğin "Site Tanıtım Cümlesi (Desc)"
        'hero_title',
        'hero_subtitle',
        'hero_button_text',
        'hero_button_link',
        'hero_background',
        'primary_color',
        'secondary_color',
        // --- Bizim eklediğimiz yeni alanlar ---
        'site_keywords',     // Senin istediğin "Anahtar Kelimeler"
        'google_analytics',  // Senin istediğin "Google Analytics Kodu"
    ];
}