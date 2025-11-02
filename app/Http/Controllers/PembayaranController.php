<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\FormulirPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            $pembayarans = Pembayaran::paginate(20);
        } else {
            // user hanya melihat pembayaran miliknya melalui formulari
            $pembayarans = Pembayaran::whereHas('formulir', function ($q) {
                $q->where('user_id', Auth::id());
            })->paginate(20);
        }
        return view('pembayaran.index', compact('pembayarans'));
    }

    public function create()
    {
        $formulirs = FormulirPendaftaran::where('user_id', Auth::id())->get();
        return view('pembayaran.create', compact('formulirs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'formulir_id' => 'required|exists:formulir_pendaftaran,id',
            'tanggal_bayar' => 'required|date',
            'metode_bayar' => 'required|in:VA,E-Wallet,Transfer Bank',
            'jumlah_awal' => 'required|numeric|min:0',
            'promo_voucher_id' => 'nullable|exists:promo,id',
            'bukti_bayar' => 'nullable|file|max:5120', // 5MB
        ]);

        if ($request->hasFile('bukti_bayar')) {
            $data['bukti_bayar'] = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        }

        $data['status'] = 'Menunggu';
        $data['kode_transaksi'] = 'TRX-' . time() . '-' . rand(1000, 9999);

        $p = Pembayaran::create($data);

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran tersimpan, menunggu verifikasi.');
    }

    public function show(Pembayaran $pembayaran)
    {
        // cek hak akses
        if (!Auth::user()->hasRole('admin') && $pembayaran->formulir->user_id != Auth::id()) abort(403);
        return view('pembayaran.show', compact('pembayaran'));
    }

    public function verify(Request $request, Pembayaran $pembayaran)
    {
        // hanya admin boleh verifikasi
        if (!Auth::user()->hasRole('admin')) abort(403);

        $data = $request->validate([
            'status' => 'required|in:Menunggu,Lunas',
            'catatan' => 'nullable|string'
        ]);

        $pembayaran->update([
            'status' => $data['status'],
            'admin_verifikasi_id' => Auth::id(),
            'verified_at' => now(),
            'catatan' => $data['catatan'] ?? null
        ]);

        return redirect()->route('pembayaran.show', $pembayaran)->with('success', 'Pembayaran diupdate.');
    }
}
