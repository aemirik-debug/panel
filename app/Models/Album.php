<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Album extends Model
{
    use HasFactory;
    use TenantConnection;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'show_on',
        'images',
        'cover_image',
        'order',
        'is_active',
    ];

    protected $casts = [
        'show_on' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $album): void {
            if (blank($album->slug) && filled($album->title)) {
                $album->slug = Str::slug($album->title);
            }

            // Defensive guard: never persist more than 25 images per album.
            $images = array_values(array_filter($album->images ?? []));
            if (count($images) > 25) {
                $images = array_slice($images, 0, 25);
            }
            $album->images = $images;

            // Keep cover image consistent with uploaded images.
            if (! empty($images)) {
                if (blank($album->cover_image) || ! in_array($album->cover_image, $images, true)) {
                    $album->cover_image = $images[0];
                }
            } else {
                $album->cover_image = null;
            }
        });
    }

    public function getCoverImageAttribute(): ?string
    {
        $images = $this->images ?? [];

        if (filled($this->attributes['cover_image'] ?? null)) {
            return $this->attributes['cover_image'];
        }

        return $images[0] ?? null;
    }
}
