<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

   public function store(Request $request)
{
    $request->validate([
        'login' => ['required', 'string'], // Bisa username atau email
        'password' => ['required', 'string'],
    ]);

    $loginInput = $request->input('login');

    // Deteksi input login apakah email atau username
    $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    $credentials = [
        $fieldType => $loginInput,
        'password' => $request->password,
    ];

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        if (Auth::user()->hasRole('admin')) {
            return redirect()->intended('/admin');
        } else {
            return redirect()->intended('/dashboard');
        }
     
    }

    return back()->withErrors([
        'login' => 'Username/Email atau password salah.',
    ]);
}


    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
