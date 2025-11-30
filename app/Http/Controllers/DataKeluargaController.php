<?php

namespace App\Http\Controllers;

use App\Models\FormulirPendaftaran;
use App\Models\OrangTua;
use App\Models\Wali;
use App\Models\DokumenPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DataKeluargaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Cek apakah sudah mengisi formulir
        $formulir = FormulirPendaftaran::where('user_id', $user->id)->first();

        if (!$formulir) {
            return view('data-keluarga.blocked', [
                'message' => 'Anda harus mengisi formulir pendaftaran terlebih dahulu sebelum mengisi data keluarga.',
                'route' => route('formulir.index'),
                'buttonText' => 'Isi Formulir Pendaftaran'
            ]);
        }

        // Cek apakah sudah upload dokumen (minimal 1 dokumen)
        $dokumenCount = DokumenPendaftaran::where('formulir_id', $formulir->id)->count();
        if ($dokumenCount === 0) {
            return view('data-keluarga.blocked', [
                'message' => 'Anda harus mengupload dokumen terlebih dahulu sebelum mengisi data keluarga.',
                'route' => route('dokumen.index'),
                'buttonText' => 'Upload Dokumen'
            ]);
        }

        try {
            // Ambil data orang tua (dalam 1 record)
            $orangTua = OrangTua::where('formulir_id', $formulir->id)->first();
            $wali = Wali::where('formulir_id', $formulir->id)->first();

            // Tentukan tipe data yang sudah dipilih
            $selectedType = null;
            if ($orangTua) {
                $selectedType = 'orang_tua';
            } elseif ($wali) {
                $selectedType = 'wali';
            }

            // Jika ada parameter type dari request, gunakan itu
            $requestType = $request->get('type');
            if ($requestType && in_array($requestType, ['orang_tua', 'wali'])) {
                $selectedType = $requestType;
            }

            return view('data-keluarga.index', compact('formulir', 'orangTua', 'wali', 'selectedType'));
        } catch (\Exception $e) {
            \Log::error('Error loading data keluarga: ' . $e->getMessage());

            return view('data-keluarga.index', [
                'formulir' => $formulir,
                'orangTua' => null,
                'wali' => null,
                'selectedType' => null
            ])->with('error', 'Terjadi kesalahan saat memuat data. Silakan coba lagi.');
        }
    }

    // Method baru untuk memilih tipe data
    public function selectType(Request $request)
    {
        $request->validate([
            'type' => 'required|in:orang_tua,wali'
        ]);

        return redirect()->route('data-keluarga.index', ['type' => $request->type]);
    }

    // Method untuk kembali ke pemilihan tipe
    public function resetType()
    {
        return redirect()->route('data-keluarga.index');
    }

    public function storeCombined(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'formulir_id' => 'required|exists:formulir_pendaftaran,id',
            // Validasi data ayah
            'ayah.nama' => 'required|string|max:100',
            'ayah.no_hp' => 'required|string|max:20',
            'ayah.alamat' => 'required|string',
            'ayah.nik' => 'nullable|string|max:20',
            'ayah.tanggal_lahir' => 'nullable|date',
            'ayah.pekerjaan' => 'nullable|string|max:100',
            'ayah.penghasilan' => 'nullable|numeric|min:0',
            // Validasi data ibu
            'ibu.nama' => 'required|string|max:100',
            'ibu.no_hp' => 'required|string|max:20',
            'ibu.alamat' => 'required|string',
            'ibu.nik' => 'nullable|string|max:20',
            'ibu.tanggal_lahir' => 'nullable|date',
            'ibu.pekerjaan' => 'nullable|string|max:100',
            'ibu.penghasilan' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi. Silakan periksa data Anda.');
        }

        DB::beginTransaction();

        try {
            $formulir = FormulirPendaftaran::find($request->formulir_id);

            if ($formulir->user_id !== auth()->id()) {
                abort(403);
            }

            // Hapus data wali jika ada (karena memilih orang tua)
            Wali::where('formulir_id', $request->formulir_id)->delete();

            // Simpan/Update data orang tua dalam 1 record
            $orangTuaData = [
                'formulir_id' => $request->formulir_id,
                
                // Data Ayah - field sesuai database
                'nama_ayah' => $request->input('ayah.nama'),
                'tanggal_lahir_ayah' => $request->input('ayah.tanggal_lahir'),
                'pekerjaan_ayah' => $request->input('ayah.pekerjaan'),
                'penghasilan_ayah' => $request->input('ayah.penghasilan'),
                'nik_ayah' => $request->input('ayah.nik'),
                'no_hp_ayah' => $request->input('ayah.no_hp'),
                'alamat_ayah' => $request->input('ayah.alamat'),
                
                // Data Ibu - field sesuai database
                'nama_ibu' => $request->input('ibu.nama'),
                'tanggal_lahir_ibu' => $request->input('ibu.tanggal_lahir'),
                'pekerjaan_ibu' => $request->input('ibu.pekerjaan'),
                'penghasilan_ibu' => $request->input('ibu.penghasilan'),
                'nik_ibu' => $request->input('ibu.nik'),
                'no_hp_ibu' => $request->input('ibu.no_hp'),
                'alamat_ibu' => $request->input('ibu.alamat'),
            ];

            OrangTua::updateOrCreate(
                ['formulir_id' => $request->formulir_id],
                $orangTuaData
            );

            DB::commit();

            return redirect()->route('data-keluarga.index', ['type' => 'orang_tua'])
                ->with('success', 'Data orang tua berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error storing combined orang tua: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function storeWali(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'formulir_id' => 'required|exists:formulir_pendaftaran,id',
            'nama_wali' => 'required|string|max:100',
            'alamat_wali' => 'required|string',
            'no_hp_wali' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi.');
        }

        DB::beginTransaction();

        try {
            $formulir = FormulirPendaftaran::find($request->formulir_id);

            if ($formulir->user_id !== auth()->id()) {
                abort(403);
            }

            // Hapus data orang tua jika ada (karena memilih wali)
            OrangTua::where('formulir_id', $request->formulir_id)->delete();

            // Update or create data wali
            Wali::updateOrCreate(
                ['formulir_id' => $request->formulir_id],
                [
                    'nama_wali' => $request->nama_wali,
                    'alamat_wali' => $request->alamat_wali,
                    'no_hp_wali' => $request->no_hp_wali,
                ]
            );

            DB::commit();

            return redirect()->route('data-keluarga.index', ['type' => 'wali'])
                ->with('success', 'Data wali berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error storing wali: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function deleteData(Request $request)
    {
        $request->validate([
            'formulir_id' => 'required|exists:formulir_pendaftaran,id',
            'type' => 'required|in:orang_tua,wali'
        ]);

        try {
            $formulir = FormulirPendaftaran::find($request->formulir_id);

            if ($formulir->user_id !== auth()->id()) {
                abort(403);
            }

            if ($request->type === 'orang_tua') {
                OrangTua::where('formulir_id', $request->formulir_id)->delete();
                $message = 'Data orang tua berhasil dihapus!';
            } else {
                Wali::where('formulir_id', $request->formulir_id)->delete();
                $message = 'Data wali berhasil dihapus!';
            }

            return redirect()->route('data-keluarga.reset-type')
                ->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Error deleting data: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method lama untuk backward compatibility
    public function storeOrangTua(Request $request)
    {
        return $this->storeCombined($request);
    }
}