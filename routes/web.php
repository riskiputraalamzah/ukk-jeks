<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
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
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ✅ Redirect root ke login
Route::get('/', fn() => redirect('/login'));

// ✅ Dashboard umum untuk semua user yang login
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Profile routes (semua user login bisa akses)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ Grup untuk user biasa
Route::middleware(['auth'])->group(function () {
    Route::resource('formulir', FormulirPendaftaranController::class);
    Route::resource('dokumen', DokumenPendaftaranController::class);
    Route::resource('orangtua', OrangTuaController::class);
    Route::resource('wali', WaliController::class);
    Route::resource('pembayaran', PembayaranController::class)->except(['edit','update']);
});

Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('gelombang', GelombangPendaftaranController::class);
    Route::resource('promo', PromoController::class);
    Route::resource('jurusan', JurusanController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('users', UserController::class);
});


// ✅ Auth routes (bawaan Breeze)
require __DIR__ . '/auth.php';
