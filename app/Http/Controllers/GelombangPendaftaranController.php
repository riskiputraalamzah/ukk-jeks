<?php

namespace App\Http\Controllers;

use App\Models\GelombangPendaftaran;
use Illuminate\Http\Request;

class GelombangPendaftaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin');
    }

    public function index()
    {
        $gelombangs = GelombangPendaftaran::paginate(15);
        return view('admin.gelombang.index', compact('gelombangs'));
    }

    public function create()
    {
        $gelombang = new GelombangPendaftaran();
        return view('admin.gelombang.create', compact('gelombang'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_gelombang' => 'required|string|max:50',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'limit_siswa' => 'required|integer|min:0',
            'harga' => 'required|integer',
            'catatan' => 'nullable|string',
        ]);

        GelombangPendaftaran::create($data);

        return redirect()->route('admin.gelombang.index')->with('success', 'Gelombang berhasil dibuat.');
    }

    public function show(GelombangPendaftaran $gelombang)
    {
        return view('admin.gelombang.show', compact('gelombang'));
    }

    public function edit(GelombangPendaftaran $gelombang)
    {
        return view('admin.gelombang.edit', compact('gelombang'));
    }

    public function update(Request $request, GelombangPendaftaran $gelombang)
    {
        $data = $request->validate([
            'nama_gelombang' => 'required|string|max:50',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'limit_siswa' => 'required|integer|min:0',
             'harga' => 'required|integer',
            'catatan' => 'nullable|string',
        ]);

        $gelombang->update($data);

        return redirect()->route('admin.gelombang.index')->with('success', 'Gelombang berhasil diperbarui.');
    }

    public function destroy(GelombangPendaftaran $gelombang)
    {
        $gelombang->delete();

        return redirect()->route('admin.gelombang.index')->with('success', 'Gelombang berhasil dihapus.');
    }
}
