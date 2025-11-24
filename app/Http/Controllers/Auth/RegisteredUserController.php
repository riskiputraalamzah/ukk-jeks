<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|string|email|max:255|unique:users',
            'no_hp'    => 'required|string|max:20',
            'password' => 'required|confirmed|min:6',
        ]);

        // SIMPAN USER
        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'password' => Hash::make($request->password),  // FIX UTAMA
        ]);

        // OPTIONAL jika kamu pakai roles
        if (Role::where('name', 'user')->exists()) {
            $user->roles()->attach(Role::where('name', 'user')->first()->id);
        }

        Auth::login($user);

        // FIX REDIRECT
        return redirect()->route('dashboard')
            ->with('success', 'Akun berhasil dibuat!');
    }
}
