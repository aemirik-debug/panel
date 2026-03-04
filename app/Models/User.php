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

    protected $fillable = [
        'name',
        'email',
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
        // Şimdilik herkese izin veriyoruz. 
        // İleride 'Süper Admin sadece ana panele, müşteri sadece kendi paneline' kuralını buraya yazacağız.
        return true;
    }
}