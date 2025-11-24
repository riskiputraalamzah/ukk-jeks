@extends('layouts.admin')


@section('title', 'Dashboard Admin')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


<div class="container-fluid">

    <!-- ROW 1 — CARD STATISTICS -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Pendaftar</h6>
                    <h2 class="fw-bold">{{ $total_pendaftar }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Jurusan</h6>
                    <h2 class="fw-bold">{{ $total_jurusan }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Gelombang Aktif</h6>
                    <h2 class="fw-bold">{{ $total_gelombang }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Promo Aktif</h6>
                    <h2 class="fw-bold">{{ $promo_aktif }}</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- ROW 2 — GRAFIK -->
    <div class="row g-3 mb-4">

        <!-- Grafik Pendaftar -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    Grafik Pendaftar Per Hari
                </div>
                <div class="card-body">
                    <canvas id="chartPendaftar"></canvas>
                </div>
            </div>
        </div>

        <!-- Grafik Income -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    Grafik Pemasukan
                </div>
                <div class="card-body">
                    <canvas id="chartIncome"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- ROW 3 — PENDAFTAR BARU -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white">
            Pendaftar Baru
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendaftar_baru as $p)
                    <tr>
                        <td>{{ $p->user->username ?? '-' }}</td>
                        <td>{{ $p->jurusan->nama_jurusan ?? '-' }}</td>
                        <td>{{ $p->created_at->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge bg-info text-dark">Baru</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Grafik Pendaftar
    const ctx1 = document.getElementById('chartPendaftar');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($grafik_pendaftar->pluck('tanggal')) !!},
            datasets: [{
                label: 'Pendaftar',
                data: {!! json_encode($grafik_pendaftar->pluck('total')) !!},
            }]
        }
    });

    // Grafik Income
    const ctx2 = document.getElementById('chartIncome');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: {!! json_encode($grafik_income->pluck('bulan')) !!},
            datasets: [{
                label: 'Pemasukan',
                data: {!! json_encode($grafik_income->pluck('total')) !!},
            }]
        }
    });
</script>
@endsection
