@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div class="auth-card p-4 p-md-5" style="max-width: 420px; width:100%; margin:auto;">

    <h3 class="text-center text-white fw-bold mb-3">Reset Password</h3>
    <p class="text-center text-white-50 mb-4">Masukkan email kamu untuk menerima link reset password.</p>

    <!-- Alert Success -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" style="background: rgba(34, 197, 94, 0.2); border: 1px solid rgba(34, 197, 94, 0.5); color: #bbf7d0;">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
        </div>
    @endif

    <!-- Alert Error -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.5); color: #fecaca;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <label class="text-white fw-semibold">Email</label>
            <input type="email" name="email" class="form-control auth-input" value="{{ old('email') }}" required autofocus>
        </div>

        <button class="btn btn-primary auth-btn w-100">Kirim Link Reset</button>

        <div class="auth-link text-center mt-3 text-white">
            <a href="{{ route('login') }}">Kembali ke Login</a>
        </div>
    </form>

</div>

<style>
.alert {
    border-radius: 12px;
    backdrop-filter: blur(10px);
    margin-bottom: 1.5rem;
}

.alert-success {
    background: rgba(34, 197, 94, 0.15) !important;
    border: 1px solid rgba(34, 197, 94, 0.4) !important;
    color: #dcfce7 !important;
}

.alert-danger {
    background: rgba(239, 68, 68, 0.15) !important;
    border: 1px solid rgba(239, 68, 68, 0.4) !important;
    color: #fecaca !important;
}

.auth-input {
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 10px;
    padding: 12px 16px;
    color: #333;
    margin-top: 8px;
}

.auth-input:focus {
    background: rgba(255, 255, 255, 0.95);
    border-color: rgba(59, 130, 246, 0.5);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    color: #333;
}

.auth-btn {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.auth-btn:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.auth-link a {
    color: #93c5fd;
    text-decoration: none;
    transition: color 0.3s ease;
}

.auth-link a:hover {
    color: #60a5fa;
    text-decoration: underline;
}
</style>

<!-- Tambahkan Font Awesome untuk icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
// Auto close alert setelah 5 detik
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endsection