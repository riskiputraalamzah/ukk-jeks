<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\FormulirPendaftaran;
use App\Models\DokumenPendaftaran;
use App\Models\OrangTua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        // Setup Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Cek apakah sudah mengisi formulir
        $formulir = FormulirPendaftaran::where('user_id', $user->id)->first();

        if (!$formulir) {
            return view('pembayaran.blocked', [
                'message' => 'Anda harus mengisi formulir pendaftaran terlebih dahulu sebelum melakukan pembayaran.',
                'route' => route('formulir.index'),
                'buttonText' => 'Isi Formulir Pendaftaran'
            ]);
        }

        // Cek apakah sudah upload dokumen (minimal 1 dokumen)
        $dokumenCount = DokumenPendaftaran::where('formulir_id', $formulir->id)->count();
        if ($dokumenCount === 0) {
            return view('pembayaran.blocked', [
                'message' => 'Anda harus mengupload dokumen terlebih dahulu sebelum melakukan pembayaran.',
                'route' => route('dokumen.index'),
                'buttonText' => 'Upload Dokumen'
            ]);
        }

        // Cek apakah sudah mengisi data keluarga
        $orangTua = OrangTua::where('formulir_id', $formulir->id)->first();
        if (!$orangTua) {
            return view('pembayaran.blocked', [
                'message' => 'Anda harus mengisi data orang tua terlebih dahulu sebelum melakukan pembayaran.',
                'route' => route('data-keluarga.index'),
                'buttonText' => 'Isi Data Keluarga'
            ]);
        }
        
        if ($user->hasRole('admin')) {
            $pembayarans = Pembayaran::with(['formulir', 'formulir.user'])->latest()->paginate(20);
        } else {
            $pembayarans = Pembayaran::whereHas('formulir', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->latest()->paginate(20);
        }
        
        return view('pembayaran.index', compact('pembayarans'));
    }

    public function create()
    {
        $formulir = FormulirPendaftaran::where('user_id', Auth::id())->first();
        
        if (!$formulir) {
            return view('pembayaran.blocked', [
                'message' => 'Anda harus mengisi formulir pendaftaran terlebih dahulu sebelum melakukan pembayaran.',
                'route' => route('formulir.index'),
                'buttonText' => 'Isi Formulir Pendaftaran'
            ]);
        }

        // Cek apakah sudah upload dokumen (minimal 1 dokumen)
        $dokumenCount = DokumenPendaftaran::where('formulir_id', $formulir->id)->count();
        if ($dokumenCount === 0) {
            return view('pembayaran.blocked', [
                'message' => 'Anda harus mengupload dokumen terlebih dahulu sebelum melakukan pembayaran.',
                'route' => route('dokumen.index'),
                'buttonText' => 'Upload Dokumen'
            ]);
        }

        // Cek apakah sudah mengisi data keluarga
        $orangTua = OrangTua::where('formulir_id', $formulir->id)->first();
        if (!$orangTua) {
            return view('pembayaran.blocked', [
                'message' => 'Anda harus mengisi data orang tua terlebih dahulu sebelum melakukan pembayaran.',
                'route' => route('data-keluarga.index'),
                'buttonText' => 'Isi Data Keluarga'
            ]);
        }

        // Cek apakah sudah ada pembayaran yang pending/lunas
        $existingPayment = Pembayaran::where('formulir_id', $formulir->id)
            ->whereIn('status', ['Lunas', 'Pending'])
            ->first();

        if ($existingPayment) {
            return redirect()->route('pembayaran.show', $existingPayment)
                ->with('info', 'Anda sudah memiliki transaksi pembayaran.');
        }

        return view('pembayaran.create', compact('formulir'));
    }

    public function store(Request $request)
    {
        $formulir = FormulirPendaftaran::where('user_id', Auth::id())->firstOrFail();

        // Validasi
        $request->validate([
            'promo_voucher_id' => 'nullable|exists:promo,id',
        ]);

        // Hitung jumlah pembayaran
        $amount = 250000; // Default amount, bisa disesuaikan

        // Buat record pembayaran
        $pembayaran = Pembayaran::create([
            'formulir_id' => $formulir->id,
            'jumlah_awal' => $amount,
            'jumlah_akhir' => $amount,
            'promo_voucher_id' => $request->promo_voucher_id,
            'status' => 'Menunggu',
            'kode_transaksi' => 'TRX-' . time() . '-' . rand(1000, 9999),
        ]);

        // Generate Midtrans transaction
        try {
            $transactionDetails = [
                'order_id' => $pembayaran->kode_transaksi,
                'gross_amount' => $amount,
            ];

            $customerDetails = [
                'first_name' => $formulir->nama_lengkap,
                'email' => Auth::user()->email,
                'phone' => $formulir->no_hp,
            ];

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'callbacks' => [
                    'finish' => route('pembayaran.status')
                ]
            ];

            $snapToken = Snap::getSnapToken($params);
            
            // Update pembayaran dengan data Midtrans
            $pembayaran->update([
                'midtrans_order_id' => $transactionDetails['order_id'],
                'status' => 'Pending'
            ]);

            return view('pembayaran.payment', compact('snapToken', 'pembayaran'));

        } catch (\Exception $e) {
            $pembayaran->delete(); // Hapus record jika gagal
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Pembayaran $pembayaran)
    {
        // Cek hak akses
        if (!Auth::user()->hasRole('admin') && $pembayaran->formulir->user_id != Auth::id()) {
            abort(403);
        }
        
        return view('pembayaran.show', compact('pembayaran'));
    }

    public function status(Request $request)
    {
        $orderId = $request->order_id;
        $pembayaran = Pembayaran::where('midtrans_order_id', $orderId)->firstOrFail();

        // Untuk production, sebaiknya gunakan webhook/notification
        // Ini hanya contoh sederhana
        return view('pembayaran.status', compact('pembayaran'));
    }

    public function notification(Request $request)
    {
        // Handle Midtrans notification (webhook)
        // Implementasi lengkap butuh logic untuk handle berbagai status
    }

    public function verify(Request $request, Pembayaran $pembayaran)
    {
        if (!Auth::user()->hasRole('admin')) abort(403);

        $data = $request->validate([
            'status' => 'required|in:Menunggu,Lunas,Pending,Failed,Expired',
            'catatan' => 'nullable|string'
        ]);

        $pembayaran->update([
            'status' => $data['status'],
            'admin_verifikasi_id' => Auth::id(),
            'verified_at' => now(),
            'catatan' => $data['catatan'] ?? null
        ]);

        return redirect()->route('pembayaran.show', $pembayaran)->with('success', 'Status pembayaran diperbarui.');
    }
}