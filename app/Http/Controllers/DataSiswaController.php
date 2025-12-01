<?php

namespace App\Http\Controllers;

use App\Models\FormulirPendaftaran;
use App\Models\DokumenPendaftaran;
use App\Models\{OrangTua, Wali};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataSiswaController extends Controller
{
    public function index()
    {
        // Ambil user yang login
        $user = Auth::user();

        // Cek apakah sudah mengisi formulir
        $formulir = FormulirPendaftaran::where('user_id', $user->id)->first();

        if (!$formulir) {
            return view('data-siswa.blocked', [
                'message' => 'Anda harus mengisi formulir pendaftaran terlebih dahulu sebelum melihat data siswa.',
                'route' => route('formulir.index'),
                'buttonText' => 'Isi Formulir Pendaftaran'
            ]);
        }

        // Cek apakah sudah upload dokumen (minimal 1 dokumen)
        $dokumenCount = DokumenPendaftaran::where('formulir_id', $formulir->id)->count();
        if ($dokumenCount === 0) {
            return view('data-siswa.blocked', [
                'message' => 'Anda harus mengupload dokumen terlebih dahulu sebelum melihat data siswa.',
                'route' => route('dokumen.index'),
                'buttonText' => 'Upload Dokumen'
            ]);
        }

        // Cek apakah sudah mengisi data keluarga
        $orangTua = OrangTua::where('formulir_id', $formulir->id)->first();
        $wali = Wali::where('formulir_id', $formulir->id)->first();

        $dataOrangTuaOrWali = $orangTua || $wali;

        if (!$dataOrangTuaOrWali) {
            return view('data-siswa.blocked', [
                'message' => 'Anda harus mengisi data orang tua terlebih dahulu sebelum melihat data siswa.',
                'route' => route('data-keluarga.index'),
                'buttonText' => 'Isi Data Keluarga'
            ]);
        }

        // Ambil formulir pendaftaran terbaru user ini
        $formulir = FormulirPendaftaran::where('user_id', $user->id)
            ->with(['jurusan', 'gelombang', 'dokumen'])
            ->latest()
            ->first();

        // Cari foto 3x4
        $fotoSiswa = null;
        if ($formulir) {
            $fotoSiswa = DokumenPendaftaran::where('formulir_id', $formulir->id)
                ->where('jenis_dokumen', 'foto_3x4')
                ->first();
        }

        return view('data-siswa.index', compact('formulir', 'fotoSiswa'));
    }
}
