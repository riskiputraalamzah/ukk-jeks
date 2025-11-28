<?php

namespace App\Http\Controllers;

use App\Models\FormulirPendaftaran;
use App\Models\OrangTua;
use App\Models\Wali;
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

    public function index()
    {
        $formulir = auth()->user()->formulir()->first();

        if (!$formulir) {
            return redirect()->route('formulir.index')
                ->with('error', 'Anda harus mengisi formulir pendaftaran terlebih dahulu.');
        }

        try {
            // Ambil data berdasarkan struktur database yang ada (orang_lua)
            $ayah = OrangTua::where('formulir_id', $formulir->id)
                ->where('jenis_orangtua', 'ayah')
                ->first();

            $ibu = OrangTua::where('formulir_id', $formulir->id)
                ->where('jenis_orangtua', 'ibu')
                ->first();

            $wali = Wali::where('formulir_id', $formulir->id)->first();

            return view('data-keluarga.index', compact('formulir', 'ayah', 'ibu', 'wali'));
        } catch (\Exception $e) {
            \Log::error('Error loading data keluarga: ' . $e->getMessage());

            return view('data-keluarga.index', [
                'formulir' => $formulir,
                'ayah' => null,
                'ibu' => null,
                'wali' => null
            ])->with('error', 'Terjadi kesalahan saat memuat data. Silakan coba lagi.');
        }
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

            // Simpan/Update data ayah
            OrangTua::updateOrCreate(
                [
                    'formulir_id' => $request->formulir_id,
                    // 'jenis_orangtua' => 'ayah'
                ],
                [
                    'nama_ayah' => $request->ayah['nama'],
                    'tanggal_lahir_ayah' => $request->ayah['tanggal_lahir'],
                    'pekerjaan_ayah' => $request->ayah['pekerjaan'],
                    'penghasilan_ayah' => $request->ayah['penghasilan'],
                    'alamat_ayah' => $request->ayah['alamat'],
                    'no_hp_ayah' => $request->ayah['no_hp'],
                    'nik_ayah' => $request->ayah['nik'],
                    'nama_ibu' => $request->ibu['nama'],
                    'tanggal_lahir_ibu' => $request->ibu['tanggal_lahir'],
                    'pekerjaan_ibu' => $request->ibu['pekerjaan'],
                    'penghasilan_ibu' => $request->ibu['penghasilan'],
                    'alamat_ibu' => $request->ibu['alamat'],
                    'no_hp_ibu' => $request->ibu['no_hp'],
                    'nik_ibu' => $request->ibu['nik']
                ]
            );



            DB::commit();

            return redirect()->route('data-keluarga.index')
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
            'nama_wali' => 'nullable|string|max:100',
            'alamat_wali' => 'nullable|string',
            'no_hp_wali' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi.');
        }

        try {
            $formulir = FormulirPendaftaran::find($request->formulir_id);

            if ($formulir->user_id !== auth()->id()) {
                abort(403);
            }

            // Update or create data wali
            Wali::updateOrCreate(
                ['formulir_id' => $request->formulir_id],
                $request->except(['_token', 'formulir_id'])
            );

            return redirect()->route('data-keluarga.index')
                ->with('success', 'Data wali berhasil disimpan!');
        } catch (\Exception $e) {
            \Log::error('Error storing wali: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method lama untuk backward compatibility (bisa dihapus nanti)
    public function storeOrangTua(Request $request)
    {
        return $this->storeCombined($request);
    }
}
