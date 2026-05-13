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
        // Merkezi admin paneline giriş izni
        if ($panel->getId() === 'admin') {
            // Eğer rolü super_admin ise veya henüz hiç rol atanmamışsa (ilk kurulum) girişe izin ver
            return $this->role === 'super_admin' || is_null($this->role);
        }

        // Müşteri (yonetim) paneline herkes girebilir (kendi verisine ulaşır)
        return true;
    }
}