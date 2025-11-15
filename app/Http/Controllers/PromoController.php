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
        return view('admin.promo.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_promo' => 'required|string|max:100',
            'nominal_potongan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'is_aktif' => 'boolean',
        ]);

        Promo::create([
            'jenis_promo' => $data['jenis_promo'],
            'nominal_potongan' => $data['nominal_potongan'],
            'keterangan' => $data['keterangan'] ?? null,
            'is_aktif' => $request->has('is_aktif') ? 1 : 0,
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
            'jenis_promo' => 'required|string|max:100',
            'nominal_potongan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'is_aktif' => 'boolean',
        ]);

        $promo->update([
            'jenis_promo' => $data['jenis_promo'],
            'nominal_potongan' => $data['nominal_potongan'],
            'keterangan' => $data['keterangan'] ?? null,
            'is_aktif' => $request->is_aktif,
        ]);

        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil diperbarui');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil dihapus');
    }
}
