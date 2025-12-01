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

        // Cek apakah sudah mengisi data keluarga (orang tua atau wali, salah satu harus terisi)
        $orangTua = OrangTua::where('formulir_id', $formulir->id)->first();
        $wali = \App\Models\Wali::where('formulir_id', $formulir->id)->first();

        $dataKeluargaTerisi = $orangTua || $wali;

        if (!$dataKeluargaTerisi) {
            return view('pembayaran.blocked', [
                'message' => 'Anda harus mengisi data orang tua atau wali terlebih dahulu sebelum melakukan pembayaran.',
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
        $wali = \App\Models\Wali::where('formulir_id', $formulir->id)->first();

        $dataKeluargaTerisi = $orangTua || $wali;

        if (!$dataKeluargaTerisi) {
            return view('pembayaran.blocked', [
                'message' => 'Anda harus mengisi data orang tua terlebih dahulu sebelum melakukan pembayaran.',
                'route' => route('data-keluarga.index'),
                'buttonText' => 'Isi Data Keluarga'
            ]);
        }

        // Cek apakah sudah ada pembayaran yang pending
        $existingPayment = Pembayaran::where('formulir_id', $formulir->id)
            ->whereIn('status', ['Pending'])
            ->first();

        if ($existingPayment) {
            return redirect()->route('pembayaran.continue', $existingPayment)
                ->with('info', 'Anda memiliki pembayaran yang tertunda. Silakan lanjutkan pembayaran.');
        }

        // Cek apakah sudah ada pembayaran yang Lunas
        $paidPayment = Pembayaran::where('formulir_id', $formulir->id)
            ->where('status', 'Lunas')
            ->first();

        if ($paidPayment) {
            return redirect()->route('pembayaran.show', $paidPayment)
                ->with('info', 'Anda sudah memiliki pembayaran yang lunas.');
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
        // mengamil data harga berdasarkan gelombang pendaftaran (mengacu pada kolom harga di tabel gelombang)
        $amount = 250000; // Default amount, bisa disesuaikan

        // Buat record pembayaran
        $pembayaran = Pembayaran::create([
            'formulir_id' => $formulir->id,
            'jumlah_awal' => $amount,
            'jumlah_akhir' => $amount,
            'promo_voucher_id' => $request->promo_voucher_id,
            'status' => 'Pending', // Langsung set ke Pending
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
                    'finish' => route('pembayaran.callback')
                ]
            ];

            $snapToken = Snap::getSnapToken($params);

            // Update pembayaran dengan data Midtrans
            $pembayaran->update([
                'midtrans_order_id' => $transactionDetails['order_id'],
                'midtrans_response' => ['snap_token' => $snapToken] // Simpan snap token untuk continue payment
            ]);

            return view('pembayaran.payment', compact('snapToken', 'pembayaran'));

        } catch (\Exception $e) {
            $pembayaran->delete(); // Hapus record jika gagal
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Continue payment for existing pending transaction
     */
    public function continue(Pembayaran $pembayaran)
    {
        // Cek hak akses
        if (!Auth::user()->hasRole('admin') && $pembayaran->formulir->user_id != Auth::id()) {
            abort(403);
        }

        // Cek status, hanya bisa continue jika status masih Pending
        if ($pembayaran->status !== 'Pending') {
            return redirect()->route('pembayaran.show', $pembayaran)
                ->with('error', 'Tidak dapat melanjutkan pembayaran. Status: ' . $pembayaran->status);
        }

        try {
            // Cek apakah snap token masih tersimpan
            $snapToken = $pembayaran->midtrans_response['snap_token'] ?? null;

            if (!$snapToken) {
                // Generate new snap token jika tidak ada
                $transactionDetails = [
                    'order_id' => $pembayaran->kode_transaksi,
                    'gross_amount' => $pembayaran->jumlah_akhir,
                ];

                $customerDetails = [
                    'first_name' => $pembayaran->formulir->nama_lengkap,
                    'email' => Auth::user()->email,
                    'phone' => $pembayaran->formulir->no_hp,
                ];

                $params = [
                    'transaction_details' => $transactionDetails,
                    'customer_details' => $customerDetails,
                    'callbacks' => [
                        'finish' => route('pembayaran.callback')
                    ]
                ];

                $snapToken = Snap::getSnapToken($params);

                // Update snap token
                $pembayaran->update([
                    'midtrans_response' => array_merge($pembayaran->midtrans_response ?? [], ['snap_token' => $snapToken])
                ]);
            }

            return view('pembayaran.payment', compact('snapToken', 'pembayaran'));

        } catch (\Exception $e) {
            return redirect()->route('pembayaran.show', $pembayaran)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

    /**
     * Handle Midtrans callback
     */
    /**
     * Handle Midtrans callback
     */
    /**
     * Handle Midtrans callback
     */
    public function callback(Request $request)
    {
        $orderId = $request->order_id;
        $status = $request->status;
        $paymentType = $request->payment_type; // Ambil payment_type dari parameter GET

        $pembayaran = Pembayaran::where('kode_transaksi', $orderId)->first();

        if (!$pembayaran) {
            return redirect()->route('pembayaran.index')->with('error', 'Transaksi tidak ditemukan.');
        }

        // Update status dan metode bayar berdasarkan parameter dari callback URL
        if ($status === 'success') {
            // Map payment_type ke metode_bayar jika ada
            $metodeBayar = $this->mapPaymentTypeToMetodeBayar($paymentType);

            // Update status menjadi Lunas
            $pembayaran->update([
                'status' => 'Lunas',
                'tanggal_bayar' => now(),
                'metode_bayar' => $metodeBayar ?: $pembayaran->metode_bayar, // Gunakan mapping jika ada
                'midtrans_payment_type' => $paymentType ?: $pembayaran->midtrans_payment_type,
            ]);

            // Kirim data ke view dengan status sukses
            return view('pembayaran.status', [
                'pembayaran' => $pembayaran,
                'orderId' => $orderId,
                'transaksi' => $pembayaran,
                'status' => 'success',
                'message' => 'Pembayaran berhasil! Status transaksi telah diperbarui menjadi LUNAS.'
            ]);
        } elseif ($status === 'pending') {
            // Map payment_type ke metode_bayar jika ada
            $metodeBayar = $this->mapPaymentTypeToMetodeBayar($paymentType);

            // Status masih pending
            $pembayaran->update([
                'status' => 'Pending',
                'metode_bayar' => $metodeBayar ?: $pembayaran->metode_bayar, // Gunakan mapping jika ada
                'midtrans_payment_type' => $paymentType ?: $pembayaran->midtrans_payment_type,
            ]);

            return view('pembayaran.status', [
                'pembayaran' => $pembayaran,
                'orderId' => $orderId,
                'transaksi' => $pembayaran,
                'status' => 'pending',
                'message' => 'Menunggu pembayaran. Silakan selesaikan pembayaran Anda.'
            ]);
        } else {
            // Map payment_type ke metode_bayar jika ada
            $metodeBayar = $this->mapPaymentTypeToMetodeBayar($paymentType);

            // Status error atau gagal
            $pembayaran->update([
                'status' => 'Failed',
                'metode_bayar' => $metodeBayar ?: $pembayaran->metode_bayar, // Gunakan mapping jika ada
                'midtrans_payment_type' => $paymentType ?: $pembayaran->midtrans_payment_type,
            ]);

            return view('pembayaran.status', [
                'pembayaran' => $pembayaran,
                'orderId' => $orderId,
                'transaksi' => $pembayaran,
                'status' => 'error',
                'message' => 'Pembayaran gagal atau terjadi kesalahan.'
            ]);
        }
    }

    /**
     * Map Midtrans payment_type to metode_bayar
     */
    private function mapPaymentTypeToMetodeBayar($paymentType)
    {
        if (!$paymentType)
            return null;

        $mapping = [
            'bank_transfer' => 'Transfer Bank',
            'bca_va' => 'VA',
            'bni_va' => 'VA',
            'bri_va' => 'VA',
            'mandiri_va' => 'VA',
            'permata_va' => 'VA',
            'gopay' => 'E-Wallet',
            'shopeepay' => 'E-Wallet',
            'qris' => 'E-Wallet',
            'cstore' => 'E-Wallet',
            'credit_card' => 'Credit Card',
            'cimb_clicks' => 'Transfer Bank',
            'bca_klikbca' => 'Transfer Bank',
            'danamon_online' => 'Transfer Bank',
            'echannel' => 'Transfer Bank',
        ];

        return $mapping[$paymentType] ?? $paymentType;
    }

    /**
     * Handle Midtrans notification (webhook)
     */
    /**
     * Handle Midtrans notification (webhook)
     */
    public function notification(Request $request)
    {
        $payload = $request->all();

        // Verify signature (penting untuk security)
        // $hashed = hash("sha512", $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . config('midtrans.server_key'));
        // if ($hashed != $payload['signature_key']) {
        //     return response()->json(['message' => 'Invalid signature'], 403);
        // }

        $orderId = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;

        $pembayaran = Pembayaran::where('kode_transaksi', $orderId)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Map payment_type ke metode_bayar
        $metodeBayar = $this->mapPaymentTypeToMetodeBayar($paymentType);

        // Update status berdasarkan notifikasi Midtrans
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $pembayaran->update([
                    'status' => 'Lunas',
                    'midtrans_status' => $transactionStatus,
                    'midtrans_transaction_id' => $payload['transaction_id'],
                    'midtrans_payment_type' => $paymentType,
                    'tanggal_bayar' => now(),
                    'metode_bayar' => $metodeBayar ?: $paymentType // Gunakan mapping
                ]);
            }
        } else if ($transactionStatus == 'settlement') {
            $pembayaran->update([
                'status' => 'Lunas',
                'midtrans_status' => $transactionStatus,
                'midtrans_transaction_id' => $payload['transaction_id'],
                'midtrans_payment_type' => $paymentType,
                'tanggal_bayar' => now(),
                'metode_bayar' => $metodeBayar ?: $paymentType // Gunakan mapping
            ]);
        } else if ($transactionStatus == 'pending') {
            $pembayaran->update([
                'status' => 'Pending',
                'midtrans_status' => $transactionStatus,
                'midtrans_payment_type' => $paymentType,
                'metode_bayar' => $metodeBayar ?: $paymentType // Gunakan mapping
            ]);
        } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $pembayaran->update([
                'status' => 'Failed',
                'midtrans_status' => $transactionStatus,
                'metode_bayar' => $metodeBayar ?: $paymentType // Simpan juga jika gagal
            ]);
        }

        return response()->json(['message' => 'Notification processed']);
    }

    /**
     * Check payment status via API
     */
    public function checkStatus(Pembayaran $pembayaran)
    {
        // Cek hak akses
        if (!Auth::user()->hasRole('admin') && $pembayaran->formulir->user_id != Auth::id()) {
            abort(403);
        }

        // Untuk production, implementasi pengecekan status via Midtrans API
        // https://api-docs.midtrans.com/#get-transaction-status

        return response()->json([
            'status' => $pembayaran->status,
            'midtrans_status' => $pembayaran->midtrans_status,
            'midtrans_payment_type' => $pembayaran->midtrans_payment_type
        ]);
    }

    public function verify(Request $request, Pembayaran $pembayaran)
    {
        if (!Auth::user()->hasRole('admin'))
            abort(403);

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