<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser; // Tambahkan ini
use Filament\Panel; // Tambahkan ini
use Spatie\Permission\Traits\HasRoles; // Jika pakai spatie/laravel-permission

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    // Relasi ke model Pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->id === 1) return true;

        // Bisa akses jika punya role admin atau permission khusus
        return $this->hasAnyRole(['admin', 'kasir', 'owner']);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'pegawai_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
