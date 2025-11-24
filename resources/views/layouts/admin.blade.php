<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Admin - @yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

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
                       Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.jurusan.index') }}"
                       class="nav-link {{ request()->routeIs('admin.jurusan.*') ? 'active' : '' }}">
                       Jurusan
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.gelombang.index') }}"
                       class="nav-link {{ request()->routeIs('admin.gelombang.*') ? 'active' : '' }}">
                       Gelombang
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                       class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                       Users
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.promo.index') }}"
                       class="nav-link {{ request()->routeIs('admin.promo.*') ? 'active' : '' }}">
                       Promo
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
</body>
</html>
