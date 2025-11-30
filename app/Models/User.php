<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable;

    // Kolom yang boleh diisi saat Register / Update
    protected $fillable = [
        'username',
        'email',
        'no_hp',
        'password',
        'avatar',
    ];

    // Password harus di-hash otomatis
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting password agar auto bcrypt (Laravel 10+)
    protected $casts = [
        'password' => 'hashed',
    ];

    // Relasi ke role (many-to-many)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    // UBAH INI: dari hasMany jadi hasOne (1 user punya 1 formulir)
    public function formulir()
    {
        return $this->hasOne(FormulirPendaftaran::class, 'user_id');
    }

    // RELASI BARU: langsung ke pembayaran
    public function pembayaran()
    {
        return $this->hasOneThrough(
            Pembayaran::class,
            FormulirPendaftaran::class,
            'user_id', // Foreign key pada formulir_pendaftaran
            'formulir_id', // Foreign key pada pembayaran
            'id', // Local key pada users
            'id' // Local key pada formulir_pendaftaran
        );
    }
}