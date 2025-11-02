<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin')->except(['index', 'show']);
    }

    public function index()
    {
        $promos = Promo::paginate(15);
        return view('admin.promo.index', compact('promos'));
    }

    public function create()
    {
        return view('promo.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_promo' => 'required|string|max:100',
            'nominal_potongan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'is_aktif' => 'boolean',
        ]);
        Promo::create($data);
        return redirect()->route('promo.index')->with('success', 'Promo dibuat');
    }

    public function show(Promo $promo)
    {
        return view('promo.show', compact('promo'));
    }

    public function edit(Promo $promo)
    {
        return view('promo.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $data = $request->validate([
            'jenis_promo' => 'required|string|max:100',
            'nominal_potongan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'is_aktif' => 'boolean',
        ]);
        $promo->update($data);
        return redirect()->route('promo.index')->with('success', 'Promo diupdate');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return redirect()->route('promo.index')->with('success', 'Promo dihapus');
    }
}
