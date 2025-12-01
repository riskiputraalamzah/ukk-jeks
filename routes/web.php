<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\DataKeluargaController;
use App\Http\Controllers\StatusPendaftaranController;
use App\Http\Controllers\DataSiswaController;
use App\Http\Controllers\VerifikasiController;

use App\Http\Controllers\{
    GelombangPendaftaranController,
    PromoController,
    JurusanController,
    KelasController,
    FormulirPendaftaranController,
    DokumenPendaftaranController,
    OrangTuaController,
    WaliController,
    PembayaranController,
    UserController,
    AdminDashboardController
};

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingPageController::class, 'index'])->name('landing');


/*
|--------------------------------------------------------------------------
| User Routes (Auth Required)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard user
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Formulir pendaftaran (CRUD)
    Route::resource('formulir', FormulirPendaftaranController::class);

    // ❌ HAPUS INI - DUPLIKASI DENGAN ROUTE MANUAL DI BAWAH
    // Route::resource('dokumen', DokumenPendaftaranController::class);

    // Data orang tua
    Route::resource('orangtua', OrangTuaController::class);

    // Data wali
    Route::resource('wali', WaliController::class);

    // Pembayaran (kecuali edit dan update)
    // pembayaran midtrans routes
    Route::get('/pembayaran/status', [PembayaranController::class, 'status'])->name('pembayaran.status');
    Route::post('/pembayaran/notification', [PembayaranController::class, 'notification'])->name('pembayaran.notification');


    // Route untuk continue payment
    Route::get('/pembayaran/{pembayaran}/continue', [PembayaranController::class, 'continue'])->name('pembayaran.continue');
    Route::get('/pembayaran/callback', [PembayaranController::class, 'callback'])->name('pembayaran.callback');
    Route::get('/pembayaran/{pembayaran}/check-status', [PembayaranController::class, 'checkStatus'])->name('pembayaran.check-status');
    Route::post('/pembayaran/notification', [PembayaranController::class, 'notification'])->name('pembayaran.notification');
    Route::resource('pembayaran', PembayaranController::class)->except(['edit', 'update']);

    // Data Keluarga Routes (FORM COMBINED BARU) - YANG DIPERBAIKI
    Route::prefix('data-keluarga')->group(function () {
        Route::get('/', [DataKeluargaController::class, 'index'])->name('data-keluarga.index');
        Route::post('/select-type', [DataKeluargaController::class, 'selectType'])->name('data-keluarga.select-type');
        Route::get('/reset-type', [DataKeluargaController::class, 'resetType'])->name('data-keluarga.reset-type');
        Route::post('/combined', [DataKeluargaController::class, 'storeCombined'])->name('data-keluarga.store-combined');
        Route::post('/wali', [DataKeluargaController::class, 'storeWali'])->name('data-keluarga.store-wali');
        Route::post('/delete', [DataKeluargaController::class, 'deleteData'])->name('data-keluarga.delete');
        // Route lama untuk backward compatibility
        Route::post('/orang-tua', [DataKeluargaController::class, 'storeOrangTua'])->name('data-keluarga.store-orang-tua');
    });

    // ✅ ROUTE DOKUMEN YANG BENAR - GUNAKAN SATU SAJA
    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::post('/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::get('/dokumen/{id}/download', [DokumenController::class, 'download'])->name('dokumen.download');
    Route::delete('/dokumen/{id}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');

    // ✅ DATA SISWA ROUTE - PASTIKAN DI DALAM MIDDLEWARE AUTH
    Route::get('/data-siswa', [DataSiswaController::class, 'index'])->name('data-siswa.index');
});

// Status Pendaftaran
Route::get('/status', [StatusPendaftaranController::class, 'index'])->name('status');

// Route untuk cetak PDF
Route::get('/status/cetak-pdf/{id}', [StatusPendaftaranController::class, 'cetakPdf'])->name('status.cetak-pdf');

// Pengaturan
Route::get('/pengaturan', [ProfileController::class, 'pengaturan'])->name('pengaturan');



/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin Routes - Verifikasi
Route::middleware(['auth', 'can:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard admin
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Routes verifikasi
        Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
            Route::get('/', [VerifikasiController::class, 'index'])->name('index');
            Route::get('/riwayat', [VerifikasiController::class, 'riwayat'])->name('riwayat');
            Route::get('/{id}', [VerifikasiController::class, 'show'])->name('show');
            Route::post('/{id}/verifikasi', [VerifikasiController::class, 'verifikasi'])->name('approve');
            Route::post('/{id}/tolak', [VerifikasiController::class, 'tolak'])->name('reject');
        });

        // Resource routes lainnya...
        Route::resource('gelombang', GelombangPendaftaranController::class);
        Route::resource('promo', PromoController::class);
        Route::resource('jurusan', JurusanController::class);
        Route::resource('kelas', KelasController::class);
        Route::resource('users', UserController::class);
    });
/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';