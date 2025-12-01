<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Online | SMK Antartika</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Poppins", sans-serif;
            scroll-behavior: smooth;
        }

        .navbar {
            background-color: #0d6efd;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar .nav-link {
            color: white !important;
            font-weight: 500;
            margin-right: 10px;
            transition: 0.3s;
        }

        .navbar .nav-link:hover {
            color: #ffc107 !important;
        }

        .btn-login {
            background: white;
            color: #0d6efd;
            border-radius: 30px;
            font-weight: 600;
            padding: 8px 20px;
        }

        .hero {
            position: relative;
            color: white;
            padding: 120px 0;
            text-align: center;
            background-image: url('/images/sekolah.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100dvh;
            display: flex;
            align-items: center;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.25));
            z-index: 0;
        }

        .hero .container {
            position: relative;
            z-index: 1;
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

        section {
            scroll-margin-top: 50px;
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
                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#jurusan">Jurusan</a></li>

                    <!-- DIGABUNG MENJADI 1 -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Informasi</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#informasi">Gelombang & Promo</a></li>
                            <li><a class="dropdown-item" href="#pendaftaran">Tata Cara</a></li>
                            <li><a class="dropdown-item" href="#faq">FAQ</a></li>
                            <li><a class="dropdown-item" href="#tentang">Tentang</a></li>
                            <li><a class="dropdown-item" href="#kontak">Kontak</a></li>
                        </ul>
                    </li>


                    @if(auth()->check())

                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-login" href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : route('dashboard') }}">
                            <i class="bi bi-box-arrow-in-right"></i> {{ auth()->user()->username }}
                        </a>
                    </li>

                    @else

                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-login" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                    @endif
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

    <!-- GELOMBANG + PROMO (DIGABUNG) -->
    <section id="informasi" class="py-5 bg-white">
        <div class="container text-center">
            <h2 class="section-title">Informasi Pendaftaran</h2>

            <div class="row justify-content-center g-4 mt-4">

                <!-- GELOMBANG -->
                <div class="col-md-5">
                    <div class="card shadow-sm p-4 h-100">
                        <h4 class="fw-bold text-primary text-center">Gelombang Aktif</h4>

                        @if($gelombang)
                        @foreach ($gelombang as $g)
                        <h5 class="mt-3">{{ $g->nama_gelombang }}</h5>
                        <p class="mb-1">
                            <i class="bi bi-calendar-check"></i>
                            {{ $g->tanggal_mulai }} s/d {{ $g->tanggal_selesai }}
                        </p>
                        <p class="text-muted">{{ $g->catatan }}</p>
                        @endforeach

                        @else
                        <p class="text-muted mt-3">Belum ada gelombang aktif.</p>
                        @endif
                    </div>
                </div>

                <!-- PROMO -->
                <div class="col-md-5">
                    <div class="card shadow-sm p-4 h-100">
                        <h4 class="fw-bold text-success text-center">Promo Pendaftaran</h4>

                        @forelse($promos as $p)
                        <hr>
                        <h5 class="mt-3 text-capitalize">{{ $p->jenis_promo }}</h5>
                        <p class="mb-1">
                            <i class="bi bi-tag"></i>
                            Diskon: <strong>{{ number_format($p->nominal_potongan) }}</strong>
                        </p>
                        <p class="text-muted">{{ $p->keterangan }}</p>
                        @empty
                        <p class="text-muted mt-3">Belum ada promo aktif.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- =================== JURUSAN =================== --}}
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
                            <p class="text-muted">Jurusan {{ $j[0] }} membekali siswa dengan kemampuan unggul di
                                bidangnya.</p>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- TATA CARA PENDAFTARAN -->
                <section id="pendaftaran" class="py-5">
                    <div class="container text-center">
                        <h2 class="section-title">Tata Cara Pendaftaran</h2>

                        <div class="row g-4 justify-content-center">

                            <div class="col-md-3">
                                <div class="card shadow-sm border-0 p-4">
                                    <i class="bi bi-person-plus fs-1 text-primary mb-3"></i>
                                    <h5 class="fw-bold">1. Buat Akun</h5>
                                    <p class="text-muted">Klik <strong>Daftar</strong> lalu buat akun PPDB terlebih
                                        dahulu.</p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card shadow-sm border-0 p-4">
                                    <i class="bi bi-file-earmark-text fs-1 text-primary mb-3"></i>
                                    <h5 class="fw-bold">2. Isi Formulir</h5>
                                    <p class="text-muted">Lengkapi formulir data diri dan sekolah asal.</p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card shadow-sm border-0 p-4">
                                    <i class="bi bi-upload fs-1 text-primary mb-3"></i>
                                    <h5 class="fw-bold">3. Upload Dokumen</h5>
                                    <p class="text-muted">Unggah rapor, KK, akta kelahiran, dan dokumen lainnya.</p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card shadow-sm border-0 p-4">
                                    <i class="bi bi-check-circle fs-1 text-primary mb-3"></i>
                                    <h5 class="fw-bold">4. Verifikasi</h5>
                                    <p class="text-muted">Admin akan memeriksa data dan mengonfirmasi pendaftaran.</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>

                <!-- FAQ -->
                <section id="faq" class="py-5 bg-white">
                    <div class="container">
                        <h2 class="section-title text-center">Pertanyaan Umum (FAQ)</h2>

                        <div class="accordion mt-4" id="faqAccordion">

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="q1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#a1">
                                        Bagaimana cara mendaftar PPDB?
                                    </button>
                                </h2>
                                <div id="a1" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        Klik tombol <strong>"Daftar Sekarang"</strong> lalu ikuti langkah-langkahnya.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="q2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#a2">
                                        Apakah bisa mendaftar menggunakan HP?
                                    </button>
                                </h2>
                                <div id="a2" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        Ya! Website PPDB SMK Antartika sudah mobile friendly.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="q3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#a3">
                                        Bagaimana jika lupa password?
                                    </button>
                                </h2>
                                <div id="a3" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        Klik menu <strong>"Lupa Password"</strong> di halaman login.
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>

                <!-- TENTANG -->
                <section id="tentang" class="py-5 bg-light">
                    <div class="container text-center">
                        <h2 class="section-title">Tentang Sekolah</h2>
                        <p class="lead text-muted">
                            SMK Antartika memiliki fasilitas lengkap dan tenaga pendidik profesional.
                            Kami berkomitmen mencetak lulusan siap kerja, kompeten, dan berdaya saing tinggi.
                        </p>
                    </div>
                </section>

                <!-- KONTAK -->
                <section id="kontak" class="py-5">
                    <div class="container text-center">
                        <h2 class="section-title">Hubungi Kami</h2>

                        <p class="lead">
                            📍 Jl. Raya Siwalanpanji, Buduran, Sidoarjo<br>
                            📞 (031) 8962851<br>
                            ✉️ smks.antartika1.sda@gmail.com
                        </p>

                        <div class="mt-3">
                            <a href="#" class="btn btn-outline-primary me-1"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="btn btn-outline-primary me-1"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="btn btn-outline-primary"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                </section>



                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


</div>
</div>
</section>
<footer class="text-center p-3 bg-primary text-white">
    © {{ date('Y') }} SMK Antartika | PPDB Online
</footer>