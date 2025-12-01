<?php

namespace App\Http\Controllers;

use App\Models\FormulirPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiController extends Controller
{
    public function index()
    {
        // dd('test');
        // Ambil calon siswa yang sudah bayar tapi belum diverifikasi
        $calonSiswa = FormulirPendaftaran::with(['user', 'jurusan', 'pembayaran'])
            ->whereHas('pembayaran', function ($query) {
                $query->where('status', 'Lunas');
            })
            ->where('status_verifikasi', 'menunggu')
            ->latest()
            ->get();

        // dd($calonSiswa);
        return view('admin.verifikasi.index', compact('calonSiswa'));
    }

    public function show($id)
    {

        $calonSiswa = FormulirPendaftaran::with([
            'user',
            'jurusan',
            'gelombang',
            'pembayaran',
            'dokumen',
            'orangTua',
            'wali'
        ])->findOrFail($id);

        // Pastikan calon siswa sudah bayar dan menunggu verifikasi
        if (!$calonSiswa->isSiapDiverifikasi()) {
            return redirect()->route('admin.verifikasi.index')
                ->with('error', 'Calon siswa belum siap untuk diverifikasi.');
        }

        return view('admin.verifikasi.show', compact('calonSiswa'));
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500'
        ]);

        DB::transaction(function () use ($request, $id) {
            $calonSiswa = FormulirPendaftaran::findOrFail($id);

            if (!$calonSiswa->isSiapDiverifikasi()) {
                throw new \Exception('Calon siswa belum siap untuk diverifikasi.');
            }

            $calonSiswa->update([
                'status_verifikasi' => 'diverifikasi',
                'catatan_verifikasi' => $request->catatan,
                'admin_verifikasi_id' => auth()->id(),
                'verified_at' => now(),
            ]);
        });

        return redirect()->route('admin.verifikasi.index')
            ->with('success', 'Calon siswa berhasil diverifikasi!');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        DB::transaction(function () use ($request, $id) {
            $calonSiswa = FormulirPendaftaran::findOrFail($id);

            if (!$calonSiswa->isMenungguVerifikasi()) {
                throw new \Exception('Calon siswa tidak dalam status menunggu verifikasi.');
            }

            $calonSiswa->update([
                'status_verifikasi' => 'ditolak',
                'catatan_verifikasi' => $request->alasan_penolakan,
                'admin_verifikasi_id' => auth()->id(),
                'verified_at' => now(),
            ]);
        });

        return redirect()->route('admin.verifikasi.index')
            ->with('success', 'Calon siswa berhasil ditolak!');
    }

    public function riwayat()
    {
        $calonSiswa = FormulirPendaftaran::with(['user', 'jurusan', 'pembayaran', 'adminVerifikasi'])
            ->whereIn('status_verifikasi', ['diverifikasi', 'ditolak'])
            ->latest('verified_at')
            ->get();

        // dd($calonSiswa);

        return view('admin.verifikasi.riwayat', compact('calonSiswa'));
    }
}
