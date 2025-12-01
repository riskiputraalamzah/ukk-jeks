<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GelombangPendaftaran extends Model
{
    protected $table = 'gelombang_pendaftaran';
    protected $fillable = ['nama_gelombang','tanggal_mulai','tanggal_selesai','limit_siswa','harga','catatan'];

    public function promos()
    {
        return $this->belongsToMany(Promo::class, 'gelombang_promo', 'gelombang_id', 'promo_id')->withTimestamps();
    }

    public function formulirs()
    {
        return $this->hasMany(FormulirPendaftaran::class, 'gelombang_id');
    }
}
