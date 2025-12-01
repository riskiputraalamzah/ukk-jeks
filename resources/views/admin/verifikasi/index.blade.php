@extends('layouts.admin')

@section('title', 'Verifikasi Calon Siswa')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Verifikasi Calon Siswa</h1>
        <div>
            <a href="{{ route('admin.verifikasi.riwayat') }}" class="btn btn-outline-secondary">
                <i class="fas fa-history"></i> Lihat Riwayat
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-users me-2"></i>Calon Siswa Menunggu Verifikasi
        </div>
        <div class="card-body p-0">
            @if($calonSiswa->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>No. Pendaftaran</th>
                            <th>Nama Calon Siswa</th>
                            <th>Jurusan</th>
                            <th>Tanggal Bayar</th>
                            <th>Status Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($calonSiswa as $siswa)
                        <tr>
                            <td>{{ $siswa->nomor_pendaftaran ?? '-' }}</td>
                            <td>{{ $siswa->nama_lengkap }}</td>
                            <td>{{ $siswa->jurusan->nama ?? '-' }}</td>
                            <td>{{ $siswa->pembayaran->tanggal_bayar?->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Lunas
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.verifikasi.show', $siswa->id) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i>Detail & Verifikasi
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada calon siswa yang menunggu verifikasi</h5>
                <p class="text-muted">Semua calon siswa yang sudah bayar telah diverifikasi.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection