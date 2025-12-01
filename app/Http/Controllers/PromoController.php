<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:admin']);
    }

    public function index()
    {
        $promos = Promo::paginate(15);
        return view('admin.promo.index', compact('promos'));
    }

    public function create()
    {
        // dd('ok');
        return view('admin.promo.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_promo' => 'required|string|max:100|unique:promo',
            'nominal_potongan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'is_aktif' => 'boolean',
        ]);

        Promo::create([
            'kode_promo' => $data['kode_promo'],
            'nominal_potongan' => $data['nominal_potongan'],
            'keterangan' => $data['keterangan'] ?? null,
            'is_aktif' => $request->is_aktif,
        ]);

        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil dibuat');
    }

    public function edit(Promo $promo)
    {
        return view('admin.promo.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $data = $request->validate([
            'kode_promo' => 'required|string|max:100',
            'nominal_potongan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'is_aktif' => 'boolean',
        ]);

        // Cek apakah promo sudah digunakan
        if ($promo->pembayarans()->exists()) {
            // Jika sudah digunakan, tidak boleh ubah kode_promo dan nominal_potongan
            if ($data['kode_promo'] !== $promo->kode_promo || $data['nominal_potongan'] != $promo->nominal_potongan) {
                return redirect()->back()->with('error', 'Promo sudah digunakan dalam transaksi. Tidak dapat mengubah Kode Promo atau Nominal Potongan.');
            }
        }

        $promo->update([
            'kode_promo' => $data['kode_promo'],
            'nominal_potongan' => $data['nominal_potongan'],
            'keterangan' => $data['keterangan'] ?? null,
            'is_aktif' => $request->is_aktif,
        ]);

        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil diperbarui');
    }

    public function destroy(Promo $promo)
    {
        // Cek apakah promo sudah digunakan
        if ($promo->pembayarans()->exists()) {
            return redirect()->back()->with('error', 'Promo tidak dapat dihapus karena sudah digunakan dalam transaksi.');
        }

        $promo->delete();
        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil dihapus');
    }
}
