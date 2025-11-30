<?php

namespace App\Http\Controllers;

use App\Models\FormulirPendaftaran;
use App\Models\GelombangPendaftaran;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormulirPendaftaranController extends Controller
{
    /**
     * Menampilkan form pendaftaran
     */
    public function index()
    {
        $formulir = FormulirPendaftaran::where('user_id', auth()->id())->first();
        $gelombangs = GelombangPendaftaran::all();
        $jurusan = Jurusan::all();

        return view('formulir.index', compact('formulir', 'gelombangs','jurusan'));
    }

    /**
     * Menyimpan data formulir pendaftaran
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'nisn' => 'nullable|string|max:20|unique:formulir_pendaftaran,nisn,' . ($request->formulir_id ?? ''),
            'asal_sekolah' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'nik' => 'nullable|string|max:20|unique:formulir_pendaftaran,nik,' . ($request->formulir_id ?? ''),
            'anak_ke' => 'nullable|integer|min:1',
            'jenis_kelamin' => 'required|string|max:100',
            'alamat' => 'required|string',
            'desa' => 'required|string|max:50',
            'kelurahan' => 'required|string|max:50',
            'kecamatan' => 'required|string|max:50',
            'kota' => 'required|string|max:50',
            'jurusan_id' => 'required',
            'no_hp' => 'required|string|max:20',
            'gelombang_id' => 'required|exists:gelombang_pendaftaran,id'
        ]);



        try {
            if ($request->formulir_id) {
                // dd('masuk update');
                // Update existing formulir
                $formulir = FormulirPendaftaran::where('user_id', auth()->id())
                    ->where('id', $request->formulir_id)
                    ->firstOrFail();

                $formulir->update($validated);

                $message = 'Formulir berhasil diperbarui!';
            } else {
                // dd('masuk create');
                // Create new formulir
                $validated['user_id'] = auth()->id();
                $validated['nomor_pendaftaran'] = $this->generateNomorPendaftaran();

                FormulirPendaftaran::create($validated);

                $message = 'Formulir berhasil disimpan!';
            }

            return redirect()->route('formulir.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Generate nomor pendaftaran otomatis
     */
    private function generateNomorPendaftaran()
    {
        $tahun = date('Y');
        $bulan = date('m');
        $lastFormulir = FormulirPendaftaran::latest()->first();

        $sequence = $lastFormulir ? intval(substr($lastFormulir->nomor_pendaftaran, -4)) + 1 : 1;

        return 'PPDB' . $tahun . $bulan . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Menampilkan data formulir (jika perlu)
     */
    public function show($id)
    {
        $formulir = FormulirPendaftaran::where('user_id', auth()->id())
            ->findOrFail($id);

        return view('formulir.show', compact('formulir'));
    }
}