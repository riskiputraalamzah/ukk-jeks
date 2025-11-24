<?php

namespace App\Http\Controllers;

use App\Models\GelombangPendaftaran;
use App\Models\Promo;

class LandingPageController extends Controller
{
    public function index()
    {
        // Ambil gelombang aktif
        $gelombang = GelombangPendaftaran::all();

        // Ambil promo aktif
        $promos = Promo::where('is_aktif', 1)->get();

        return view('pages.landing', compact('gelombang', 'promos'));
    }
}
