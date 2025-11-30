<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\FormulirPendaftaran;

class DashboardController extends Controller
{
    public function index()
    {
        // Jika admin, arahkan ke dashboard admin
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        // Jika user biasa
         $user = Auth::user();
        $formulir = FormulirPendaftaran::where('user_id', $user->id)->first();
        
        return view('dashboard', compact('formulir'));
    }
}
