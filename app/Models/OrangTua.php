<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    protected $table = 'orang_tua'; // Sesuaikan dengan nama tabel di database

    protected $fillable = [
        'formulir_id',
        // 'jenis_orangtua', // jenis_orangtua
        'nama_ayah',       // nama_ayah  
        'tanggal_lahir_ayah', // tanggal_lahir_ayah
        'pekerjaan_ayah', // pekerjaan_ayah
        'penghasilan_ayah', // penghasilan_ayah
        'alamat_ayah',     // alamat_ayah
        'no_hp_ayah',      // no_hp_ayah
        'nik_ayah',         // nik_ayah
        'nama_ibu',        // nama_ibu
        'tanggal_lahir_ibu', // tanggal_lahir_ibu
        'pekerjaan_ibu',  // pekerjaan_ibu
        'penghasilan_ibu', // penghasilan_ibu
        'alamat_ibu',
        'no_hp_ibu',
        'nik_ibu',
    ];

    protected $casts = [
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
        'penghasilan_ayah' => 'decimal:2',
        'penghasilan_ibu' => 'decimal:2'
    ];

    public function formulir()
    {
        return $this->belongsTo(FormulirPendaftaran::class, 'formulir_id');
    }

    // Helper methods untuk memudahkan access data
    public function getNamaAttribute()
    {
        return $this->jenis_orangtua == 'ayah' ? $this->nama_ayah : $this->nama_ibu;
    }

    public function getTanggalLahirAttribute()
    {
        return $this->jenis_orangtua == 'ayah' ? $this->tanggal_lahir_ayah : $this->tanggal_lahir_ibu;
    }

    public function getPekerjaanAttribute()
    {
        return $this->jenis_orangtua == 'ayah' ? $this->pekerjaan_ayah : $this->pekerjaan_ibu;
    }

    public function getPenghasilanAttribute()
    {
        return $this->jenis_orangtua == 'ayah' ? $this->penghasilan_ayah : $this->penghasilan_ibu;
    }

    public function getAlamatAttribute()
    {
        return $this->jenis_orangtua == 'ayah' ? $this->alamat_ayah : $this->alamat_ibu;
    }

    public function getNoHpAttribute()
    {
        return $this->jenis_orangtua == 'ayah' ? $this->no_hp_ayah : $this->no_hp_ibu;
    }

    public function getNikAttribute()
    {
        return $this->jenis_orangtua == 'ayah' ? $this->nik_ayah : $this->nik_ibu;
    }
}
