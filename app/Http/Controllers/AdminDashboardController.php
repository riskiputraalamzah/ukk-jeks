<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jurusan;
use App\Models\Promo;
use App\Models\Pembayaran;
use App\Models\FormulirPendaftaran;
use App\Models\GelombangPendaftaran;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // CARD STATISTICS - HANYA YANG SUDAH DIVERIFIKASI
        $total_pendaftar = FormulirPendaftaran::where('status_verifikasi', 'diverifikasi')->count();
        $menunggu_verifikasi = FormulirPendaftaran::where('status_verifikasi', 'menunggu')->count();
        
        $total_jurusan = Jurusan::count();
        
        // ✅ PERBAIKAN: Sesuaikan dengan column yang ada di database
        // Coba salah satu opsi ini:
        
        // Option 1: Jika pakai column 'is_aktif' (boolean)
        // $total_gelombang = GelombangPendaftaran::where('is_aktif', 1)->count();
        
        // Option 2: Jika pakai column 'status_gelombang' (string)  
        // $total_gelombang = GelombangPendaftaran::where('status_gelombang', 'aktif')->count();
        
        // Option 3: Jika tidak ada status, hitung semua
        $total_gelombang = GelombangPendaftaran::count();
        
        $promo_aktif = Promo::where('is_aktif', 1)->count();
        $total_pembayaran = Pembayaran::sum('jumlah_akhir');

        // TABEL PENDAFTAR BARU - HANYA YANG SUDAH DIVERIFIKASI
        $pendaftar_baru = FormulirPendaftaran::with(['user', 'jurusan', 'gelombang'])
            ->where('status_verifikasi', 'diverifikasi')
            ->latest()
            ->take(5)
            ->get();

        // GRUPKAN PENDAFTAR PER TANGGAL - HANYA YANG SUDAH DIVERIFIKASI
        $grafik_pendaftar = FormulirPendaftaran::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('COUNT(*) as total')
        )
        ->where('status_verifikasi', 'diverifikasi')
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'ASC')
        ->get();

        // GRAFIK PEMBAYARAN PER BULAN
        $grafik_income = Pembayaran::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('SUM(jumlah_akhir) as total')
        )
        ->groupBy('bulan')
        ->orderBy('bulan', 'ASC')
        ->get();

        return view('admin.dashboard', compact(
            'total_pendaftar',
            'menunggu_verifikasi',
            'total_jurusan',
            'total_gelombang',
            'promo_aktif',
            'total_pembayaran',
            'pendaftar_baru',
            'grafik_pendaftar',
            'grafik_income'
        ));
    }
}