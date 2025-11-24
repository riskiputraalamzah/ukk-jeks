@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-card p-4 p-md-5" style="max-width: 420px; width:100%; margin:auto;">

        <h3 class="text-center text-dark fw-bold mb-4">Masuk ke Akun</h3>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Login (username/email) -->
            <div class="mb-3">
                <label class="text-dark fw-semibold">Username atau Email</label>
                <input type="text" name="login" class="form-control auth-input" required>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="text-dark fw-semibold">Password</label>
                <input type="password" name="password" class="form-control auth-input" required>
            </div>

            <button class="btn btn-primary auth-btn w-100 mb-3">Masuk</button>

            <div class="auth-link text-center text-dark">
                <a href="{{ route('password.request') }}">Lupa password?</a>
            </div>

            <div class="auth-link text-center mt-3 text-dark">
                Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
            </div>
        </form>

    </div>
@endsection