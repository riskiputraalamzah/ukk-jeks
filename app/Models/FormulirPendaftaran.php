<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormulirPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'formulir_pendaftaran';

    protected $fillable = [
        'user_id',
        'nomor_pendaftaran',
        'nama_lengkap',
        'jenis_kelamin',
        'nisn',
        'asal_sekolah',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'nik',
        'anak_ke',
        'alamat',
        'desa',
        'kelurahan',
        'kecamatan',
        'kota',
        'no_hp',
        'jurusan_id',
        'gelombang_id',
        'status_verifikasi',
        'catatan_verifikasi',
        'admin_verifikasi_id',
        'verified_at'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function gelombang()
    {
        return $this->belongsTo(GelombangPendaftaran::class, 'gelombang_id');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenPendaftaran::class, 'formulir_id');
    }

    public function orangTua()
    {
        return $this->hasOne(OrangTua::class, 'formulir_id');
    }

    public function wali()
    {
        return $this->hasOne(Wali::class, 'formulir_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'formulir_id');
    }

    public function adminVerifikasi()
    {
        return $this->belongsTo(User::class, 'admin_verifikasi_id');
    }

    // Helper methods untuk verifikasi
    public function isTerverifikasi()
    {
        return $this->status_verifikasi === 'diverifikasi';
    }

    public function isMenungguVerifikasi()
    {
        return $this->status_verifikasi === 'menunggu';
    }

    public function isDitolak()
    {
        return $this->status_verifikasi === 'ditolak';
    }

    public function isSudahBayar()
    {
        return $this->pembayaran && $this->pembayaran->isPaid();
    }

    public function isSiapDiverifikasi()
    {
        return $this->isSudahBayar() && $this->isMenungguVerifikasi();
    }
}