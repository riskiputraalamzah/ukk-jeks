<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;

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

Route::get('/', [LandingPageController::class, 'index'])->name('landing');


// Dashboard user biasa
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// User biasa
Route::middleware(['auth'])->group(function () {
    Route::resource('formulir', FormulirPendaftaranController::class);
    Route::resource('dokumen', DokumenPendaftaranController::class);
    Route::resource('orangtua', OrangTuaController::class);
    Route::resource('wali', WaliController::class);
    Route::resource('pembayaran', PembayaranController::class)->except(['edit', 'update']);
});

// Admin area
Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('gelombang', GelombangPendaftaranController::class);
    Route::resource('promo', PromoController::class);
    Route::resource('jurusan', JurusanController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('users', UserController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// Auth routes
require __DIR__ . '/auth.php';
