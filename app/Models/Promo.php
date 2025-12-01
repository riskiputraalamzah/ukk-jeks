<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'promo';
    protected $fillable = ['kode_promo', 'nominal_potongan', 'keterangan', 'is_aktif'];

    public function gelombangs()
    {
        return $this->belongsToMany(GelombangPendaftaran::class, 'gelombang_promo', 'promo_id', 'gelombang_id')->withTimestamps();
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'promo_voucher_id');
    }
}
