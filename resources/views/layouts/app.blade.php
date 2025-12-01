<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Reset untuk menghindari konflik dengan Tailwind */
        .custom-sidebar * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .custom-sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 5px 0 25px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid #eef2ff;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            flex-shrink: 0;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .nav-links {
            padding: 20px 0;
            flex: 1;
            overflow-y: auto;
        }

        .nav-item {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            color: #374151;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            cursor: pointer;
        }

        .nav-item:hover {
            background: #f8faff;
            color: #4f46e5;
            border-left-color: #4f46e5;
        }

        .nav-item.active {
            background: #eef2ff;
            color: #4f46e5;
            border-left-color: #4f46e5;
            font-weight: 600;
        }

        .nav-icon {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .nav-text {
            font-size: 1rem;
        }

        .sidebar-footer {
            padding: 20px 25px;
            border-top: 1px solid #eef2ff;
            background: white;
            flex-shrink: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 12px;
        }

        .user-details {
            flex: 1;
            min-width: 0;
            /* Penting untuk text-overflow */
        }

        .user-details h4 {
            font-size: 0.9rem;
            color: #374151;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-details p {
            font-size: 0.8rem;
            color: #6b7280;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Tombol Logout Styles */
        .logout-btn {
            width: 100%;
            padding: 12px 16px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .logout-btn i {
            margin-right: 8px;
            font-size: 1rem;
        }

        /* Main Content dengan sidebar */
        .main-with-sidebar {
            margin-left: 280px;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 12px;
            cursor: pointer;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .custom-sidebar {
                width: 250px;
            }

            .main-with-sidebar {
                margin-left: 250px;
            }
        }

        @media (max-width: 768px) {
            .custom-sidebar {
                transform: translateX(-100%);
            }

            .custom-sidebar.active {
                transform: translateX(0);
            }

            .main-with-sidebar {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Mobile Toggle Button -->
        <button class="mobile-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>

        @php
            $user = auth()->user();
            $formulir = $user->formulir;
            $dokumenCount = $formulir ? \App\Models\DokumenPendaftaran::where('formulir_id', $formulir->id)->count() : 0;
            $hasOrangTua = $formulir && $formulir->orangTua;
            $hasWali = $formulir && $formulir->wali;
            $hasPembayaran = $formulir && $formulir->pembayaran;
        @endphp

        <!-- Sidebar Navigation -->
        <div class="custom-sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>PPDB Online</h2>
                <p>Portal Pendaftaran Siswa</p>
            </div>

            <div class="nav-links">
                <a href="{{ route('dashboard') }}"
                    class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="{{ route('formulir.index') }}"
                    class="nav-item {{ request()->routeIs('formulir.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt nav-icon"></i>
                    <span class="nav-text">Formulir Pendaftaran</span>
                    <span class="ml-auto {{ $formulir ? 'text-green-500' : 'text-gray-400' }}">
                        <i class="fas {{ $formulir ? 'fa-check-circle' : '' }}"></i>
                    </span>
                </a>

                <a href="{{ route('dokumen.index') }}"
                    class="nav-item {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
                    <i class="fas fa-file-upload nav-icon"></i>
                    <span class="nav-text">Upload Dokumen</span>
                    <span class="ml-auto {{ $dokumenCount > 0 ? 'text-green-500' : 'text-gray-400' }}">
                        <i class="fas {{ $dokumenCount > 0 ? 'fa-check-circle' : '' }}"></i>
                    </span>
                </a>

                <a href="{{ route('data-keluarga.index') }}"
                    class="nav-item {{ request()->routeIs('data-keluarga.*') ? 'active' : '' }}">
                    <i class="fas fa-users nav-icon"></i>
                    <span class="nav-text">Data Orang Tua & Wali</span>
                    <span class="ml-auto {{ $hasOrangTua || $hasWali ? 'text-green-500' : 'text-gray-400' }}">
                        <i class="fas {{ $hasOrangTua || $hasWali ? 'fa-check-circle' : '' }}"></i>
                    </span>
                </a>

                <a href="{{ route('status') }}" class="nav-item {{ request()->routeIs('status') ? 'active' : '' }}">
                    <i class="fas fa-history nav-icon"></i>
                    <span class="nav-text">Status Pendaftaran</span>
                </a>

                <a href="{{ route('data-siswa.index') }}"
                    class="nav-item {{ request()->routeIs('data-siswa.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate nav-icon"></i>
                    <span class="nav-text">Data Siswa</span>
                    <span
                        class="ml-auto {{ $formulir && ($hasOrangTua || $hasWali) && $dokumenCount > 0 ? 'text-green-500' : 'text-gray-400' }}">
                        <i
                            class="fas {{ $formulir && ($hasOrangTua || $hasWali) && $dokumenCount > 0 ? 'fa-check-circle' : '' }}"></i>
                    </span>
                </a>

                <a href="{{ route('pembayaran.index') }}"
                    class="nav-item {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
                    <i class="fa fa-credit-card-alt nav-icon"></i>
                    <span class="nav-text">Pembayaran</span>
                    <span class="ml-auto {{ $hasPembayaran ? 'text-green-500' : 'text-gray-400' }}">
                        <i class="fas {{ $hasPembayaran ? 'fa-check-circle' : '' }}"></i>
                    </span>
                </a>
            </div>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-details">
                        <h4>{{ auth()->user()->username }}</h4>
                        <p>{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <!-- Tombol Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-with-sidebar" id="mainContent">
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.custom-sidebar');
            const mobileToggle = document.querySelector('.mobile-toggle');

            if (mobileToggle) {
                mobileToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function (event) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(event.target) && !event.target.closest('.mobile-toggle')) {
                        sidebar.classList.remove('active');
                    }
                }
            });
        });
    </script>
</body>

</html>