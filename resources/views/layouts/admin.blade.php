<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Admin - @yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tambahkan Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { background-color: #f8f9fa; }

        .navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .navbar-brand {
            color: #0d6efd !important;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .navbar-nav .nav-link {
            color: #212529 !important;
            font-weight: 500;
            transition: 0.2s;
        }

        .navbar-nav .nav-link.active,
        .navbar-nav .nav-link:hover {
            color: #0d6efd !important;
        }

        .content-wrapper { padding: 30px; }
        
        /* Badge notifikasi untuk verifikasi */
        .nav-link .badge-notif {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 0.6rem;
            padding: 2px 5px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top bg-white">
    <div class="container">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-grid-1x2-fill"></i> PPDB Admin
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                       <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>

                <!-- MENU VERIFIKASI - TAMBAHKAN INI -->
                <li class="nav-item">
                    <a href="{{ route('admin.verifikasi.index') }}"
                       class="nav-link {{ request()->routeIs('admin.verifikasi.*') ? 'active' : '' }} position-relative">
                       <i class="fas fa-check-circle me-1"></i>Verifikasi
                       @php
                           $jumlahMenunggu = \App\Models\FormulirPendaftaran::whereHas('pembayaran', function($query) {
                               $query->where('status', 'Lunas')->orWhere('midtrans_status', 'settlement');
                           })->where('status_verifikasi', 'menunggu')->count();
                       @endphp
                       @if($jumlahMenunggu > 0)
                           <span class="badge bg-danger badge-notif">{{ $jumlahMenunggu }}</span>
                       @endif
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.jurusan.index') }}"
                       class="nav-link {{ request()->routeIs('admin.jurusan.*') ? 'active' : '' }}">
                       <i class="fas fa-graduation-cap me-1"></i>Jurusan
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.gelombang.index') }}"
                       class="nav-link {{ request()->routeIs('admin.gelombang.*') ? 'active' : '' }}">
                       <i class="fas fa-wave-square me-1"></i>Gelombang
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                       class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                       <i class="fas fa-users me-1"></i>Users
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.promo.index') }}"
                       class="nav-link {{ request()->routeIs('admin.promo.*') ? 'active' : '' }}">
                       <i class="fas fa-tag me-1"></i>Promo
                    </a>
                </li>

                <!-- PROFIL DROPDOWN -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-bold d-flex align-items-center"
                       href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->avatar 
                            ? asset('storage/' . Auth::user()->avatar)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                             class="rounded-circle me-2 border" width="32" height="32">
                        {{ Auth::user()->name }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end p-3 shadow-sm"
                        style="width: 300px; border-radius: 12px;">

                        <!-- include card profile yang benar -->
                        @include('admin.profile.card')

                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

<div class="content-wrapper mt-5 pt-5">
    <div class="container">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script untuk auto-hide alert -->
<script>
    // Auto-hide alert setelah 5 detik
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

</body>
</html>