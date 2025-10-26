<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jurusan;
use App\Models\GelombangPendaftaran;
use App\Models\Pembayaran;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_users' => User::count(),
            'total_jurusan' => Jurusan::count(),
            'total_gelombang' => GelombangPendaftaran::count(),
            'total_pembayaran' => Pembayaran::count(),
        ];

        return view('admin.dashboard', compact('data'));
    }
}
