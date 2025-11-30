<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $fillable = [
        'formulir_id','tanggal_bayar','metode_bayar','jumlah_awal','promo_voucher_id','jumlah_akhir',
        'status','kode_transaksi','no_kuitansi','bukti_bayar','admin_verifikasi_id','verified_at',
        'catatan','path_nota_pdf','midtrans_order_id','midtrans_transaction_id','midtrans_status',
        'midtrans_payment_type','midtrans_response'
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'tanggal_bayar' => 'datetime',
        'verified_at' => 'datetime'
    ];

    public function formulir(){ 
        return $this->belongsTo(FormulirPendaftaran::class,'formulir_id'); 
    }
    
    public function promo(){ 
        return $this->belongsTo(Promo::class,'promo_voucher_id'); 
    }
    
    public function adminVerifikasi(){ 
        return $this->belongsTo(User::class,'admin_verifikasi_id'); 
    }

    // Helper methods
    public function isPaid()
    {
        return $this->status === 'Lunas' || $this->midtrans_status === 'settlement';
    }

    public function getAmountAttribute()
    {
        return $this->jumlah_akhir ?? $this->jumlah_awal;
    }
}