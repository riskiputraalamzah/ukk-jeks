<?php

namespace App\Http\Controllers;

use App\Models\GelombangPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GelombangPendaftaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // semua method butuh auth
        $this->middleware('can:admin')->except(['index', 'show']); // hanya admin boleh create/edit/delete
    }

    public function index()
    {
        $gelombangs = GelombangPendaftaran::paginate(15);
        return view('layouts.gelombang.index', compact('gelombangs'));
    }

    public function create()
    {
        return view('gelombang.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_gelombang' => 'required|string|max:50',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'limit_siswa' => 'required|integer|min:0',
            'catatan' => 'nullable|string',
        ]);

        GelombangPendaftaran::create($data);

        return redirect()->route('gelombang.index')->with('success', 'Gelombang dibuat.');
    }

    public function show(GelombangPendaftaran $gelombang)
    {
        return view('gelombang.show', ['gelombang' => $gelombang]);
    }

    public function edit(GelombangPendaftaran $gelombang)
    {
        return view('gelombang.edit', ['gelombang' => $gelombang]);
    }

    public function update(Request $request, GelombangPendaftaran $gelombang)
    {
        $data = $request->validate([
            'nama_gelombang' => 'required|string|max:50',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'limit_siswa' => 'required|integer|min:0',
            'catatan' => 'nullable|string',
        ]);
        $gelombang->update($data);
        return redirect()->route('gelombang.index')->with('success', 'Gelombang diupdate.');
    }

    public function destroy(GelombangPendaftaran $gelombang)
    {
        $gelombang->delete();
        return redirect()->route('gelombang.index')->with('success', 'Gelombang dihapus.');
    }
}
