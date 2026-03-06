<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Setting extends Model
{
    use TenantConnection;
    // Formdan gelecek tüm alanları buraya yazıyoruz
    protected $fillable = [
        'site_name',
        'phone',
        'whatsapp_number',
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
        'slider_display_mode',
        'services_section_title',
        'services_description',
        'cta_title',
        'cta_description',
        'primary_color',
        'secondary_color',
        // --- Bizim eklediğimiz yeni alanlar ---
        'site_keywords',     // Senin istediğin "Anahtar Kelimeler"
        'google_analytics',  // Senin istediğin "Google Analytics Kodu"
        'contact_notification_email',
        'send_contact_notifications',
        'references_section_title',
        'references_section_description',
        'portfolio_section_title',
        'portfolio_section_description',
    ];
}