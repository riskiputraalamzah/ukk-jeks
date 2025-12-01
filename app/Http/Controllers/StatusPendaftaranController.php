<?php

namespace App\Http\Controllers;

use App\Models\FormulirPendaftaran;
use App\Models\Pembayaran;
use App\Models\OrangTua;
use App\Models\Wali;
use App\Models\DokumenPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusPendaftaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $formulir = $user->formulir()->first();

        if (!$formulir) {
            return view('status.blocked', [
                'message' => 'Anda harus mengisi formulir pendaftaran terlebih dahulu sebelum melihat status pendaftaran.',
                'route' => route('formulir.index'),
                'buttonText' => 'Isi Formulir Pendaftaran'
            ]);
        }

        $pembayaran = Pembayaran::where('formulir_id', $formulir->id)->first();
        $dokumen = DokumenPendaftaran::where('formulir_id', $formulir->id)->exists();
        $orangTua = OrangTua::where('formulir_id', $formulir->id)->exists();
        $wali = Wali::where('formulir_id', $formulir->id)->exists();

        $progress = $this->getProgress($formulir, $pembayaran, $dokumen, $orangTua, $wali);
        // dd($formu
        // lir);

        // dd($pembayaran);
        return view('status-pendaftaran.index', compact('formulir', 'pembayaran', 'progress'));
    }

    private function getProgress($formulir, $pembayaran, $dokumen, $orangTua, $wali)
    {
        return [
            'akun' => [
                'completed' => true,
                'label' => 'Buat Akun'
            ],
            'formulir' => [
                'completed' => !is_null($formulir),
                'label' => 'Isi Formulir'
            ],
            'dokumen' => [
                'completed' => $dokumen,
                'label' => 'Upload Dokumen'
            ],
            'orang_tua' => [
                'completed' => $orangTua || $wali,
                'label' => 'Data Orang Tua'
            ],
            'pembayaran' => [
                'completed' => $pembayaran && $pembayaran->status === 'Lunas',
                'label' => 'Pembayaran & Verifikasi',
                'status' => $pembayaran ? $pembayaran->status : 'belum_bayar'
            ],
            'cetak_pdf' => [
                'completed' => $formulir && $formulir->status_verifikasi === 'diverifikasi',
                'label' => 'Cetak PDF Bukti Pendaftaran'
            ]
        ];
    }

    public function cetakPdf($id)
    {
        $formulir = FormulirPendaftaran::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Cek apakah sudah terverifikasi
        if ($formulir->status !== 'terverifikasi') {
            return redirect()->route('status-pendaftaran.index')
                ->with('error', 'Anda belum dapat mencetak PDF. Tunggu verifikasi admin.');
        }

        // Logic generate PDF (sementara redirect ke view dulu)
        return view('pdf.bukti-pendaftaran', compact('formulir'));
    }
}