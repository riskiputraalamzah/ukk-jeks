<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Online | SMK Antartika</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: "Poppins", sans-serif;
            scroll-behavior: smooth;
        }
        .navbar {
            background-color: #0d6efd;
        }
        /* only style regular nav links, not button links (btn-light) */
        .navbar .nav-link {
            color: white !important;
            font-weight: 500;
        }
        .hero {
            background: linear-gradient(to right, #0d6efd, #2575fc);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .hero h1 {
            font-weight: 700;
            font-size: 2.8rem;
        }
        .hero p {
            font-size: 1.2rem;
            margin-top: 10px;
        }
        .section-title {
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 40px;
        }
        .timeline-step {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .timeline-step:hover {
            transform: translateY(-5px);
        }
        .timeline-icon {
            font-size: 40px;
            color: #0d6efd;
            margin-bottom: 15px;
        }
        footer {
            background: #0d6efd;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="#">SMK ANTARTIKA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon text-white"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#jurusan">Jurusan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pendaftaran">Tata Cara</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                    <li class="nav-item"><a class="btn btn-light btn-sm ms-3" href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section id="beranda" class="hero">
        <div class="container">
            <h1>Selamat Datang di PPDB Online SMK Antartika</h1>
            <p>Daftar Sekarang dan Wujudkan Impianmu Bersama Kami</p>
            <div class="mt-4">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">Daftar Sekarang</a>
                <a href="#pendaftaran" class="btn btn-outline-light btn-lg">Lihat Tata Cara</a>
            </div>
        </div>
    </section>

    {{-- Jurusan --}}
    <section id="jurusan" class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="section-title">Jurusan Kami</h2>
            <div class="row g-4">
                @php
                    $jurusan = [
                        ['RPL', 'Rekayasa Perangkat Lunak', 'bi bi-laptop'],
                        ['TKR', 'Teknik Kendaraan Ringan', 'bi bi-truck'],
                        ['TPM', 'Teknik Pemesinan', 'bi bi-gear'],
                        ['TITL', 'Teknik Instalasi Tenaga Listrik', 'bi bi-lightning-charge'],
                        ['TEI', 'Teknik Elektronika Industri', 'bi bi-cpu'],
                    ];
                @endphp
                @foreach($jurusan as $j)
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center">
                                <i class="{{ $j[2] }} display-4 text-primary mb-3"></i>
                                <h5 class="fw-bold">{{ $j[1] }}</h5>
                                <p class="text-muted">Jurusan {{ $j[0] }} membekali siswa dengan kemampuan unggul di bidangnya.</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Tata Cara Pendaftaran --}}
    <section id="pendaftaran" class="py-5">
        <div class="container text-center">
            <h2 class="section-title">Tata Cara Pendaftaran</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-3">
                    <div class="timeline-step">
                        <i class="bi bi-person-plus timeline-icon"></i>
                        <h5 class="fw-bold">1. Buat Akun</h5>
                        <p class="text-muted">Klik tombol <strong>Daftar</strong> di halaman utama untuk membuat akun PPDB.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="timeline-step">
                        <i class="bi bi-file-earmark-text timeline-icon"></i>
                        <h5 class="fw-bold">2. Isi Formulir</h5>
                        <p class="text-muted">Masukkan data diri lengkap dan pastikan semua informasi benar.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="timeline-step">
                        <i class="bi bi-upload timeline-icon"></i>
                        <h5 class="fw-bold">3. Upload Dokumen</h5>
                        <p class="text-muted">Unggah dokumen yang dibutuhkan seperti rapor dan akta kelahiran.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="timeline-step">
                        <i class="bi bi-cash-stack timeline-icon"></i>
                        <h5 class="fw-bold">4. Lakukan Pembayaran</h5>
                        <p class="text-muted">Bayar biaya pendaftaran sesuai petunjuk, lalu tunggu verifikasi dari admin.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="timeline-step">
                        <i class="bi bi-check-circle timeline-icon"></i>
                        <h5 class="fw-bold">5. Selesai!</h5>
                        <p class="text-muted">Setelah diverifikasi, kamu resmi terdaftar sebagai calon siswa SMK Antartika.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tentang --}}
    <section id="tentang" class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="section-title">Tentang Sekolah</h2>
            <p class="lead text-muted">SMK Antartika berkomitmen mencetak generasi unggul dan siap kerja. 
            Kami menyediakan fasilitas modern dan tenaga pengajar berpengalaman untuk mendukung masa depanmu.</p>
        </div>
    </section>

    {{-- Kontak --}}
    <section id="kontak" class="py-5">
        <div class="container text-center">
            <h2 class="section-title">Hubungi Kami</h2>
            <p class="lead">📍 Jl. Raya Siwalanpanji 61252, Buduran, Sidoarjo<br>
                📞 (031) 8962851 Mon-Fri 06.30 - 16.30| ✉️ info smks.antartika1.sda@gmail.com</p>

            <div class="mt-3">
                <a href="#" class="btn btn-outline-primary"><i class="bi bi-facebook"></i></a>
                <a href="#" class="btn btn-outline-info"><i class="bi bi-twitter"></i></a>
                <a href="#" class="btn btn-outline-danger"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer>
        <p>&copy; {{ date('Y') }} SMK Antartika | PPDB Online</p>
        <a href="{{ route('login') }}" class="text-white-50 small">Masuk sebagai Admin</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
