<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Announcement extends Model
{
    use HasFactory, TenantConnection;

    protected $fillable = [
        'title',
        'content',
        'image',
        'button_text',
        'button_url',
        'type',              // 'modal', 'banner', 'popup'
        'color_scheme',      // 'primary', 'success', 'warning', 'danger'
        'starts_at',
        'ends_at',
        'is_active',
        'view_count',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'view_count' => 'integer',
    ];

    protected $appends = ['image_url'];

    /**
     * Resim URL'sini döndür
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        return asset('storage/' . $this->image);
    }

    /**
     * Aktif ve yayın tarihinde olan duyuruları getir
     */
    public function scopeActive($query)
    {
        $now = now();
        
        return $query
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', $now);
            });
    }

    /**
     * Duyurunun görüntülenme sayısını artır
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }
}
