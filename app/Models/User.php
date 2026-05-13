<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser; // Bunu ekledik
use Filament\Panel; // Bunu ekledik
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser // Buraya 'implements FilamentUser' ekledik
{
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_SITE_MANAGER = 'site_manager';

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Filament'in panele giriş izni için bu fonksiyonu eklemelisin
    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->role === self::ROLE_SUPER_ADMIN,
            'app' => in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_SITE_MANAGER], true),
            default => false,
        };
    }
}