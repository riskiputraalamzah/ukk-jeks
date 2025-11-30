@extends('layouts.admin')

@section('title', 'Riwayat Verifikasi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Riwayat Verifikasi</h1>
        <a href="{{ route('admin.verifikasi.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Verifikasi
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-secondary text-white">
            <i class="fas fa-history me-2"></i>Riwayat Verifikasi Calon Siswa
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
                                <th>Status Verifikasi</th>
                                <th>Admin Verifikasi</th>
                                <th>Tanggal Verifikasi</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calonSiswa as $siswa)
                            <tr>
                                <td>{{ $siswa->nomor_pendaftaran ?? '-' }}</td>
                                <td>{{ $siswa->nama_lengkap }}</td>
                                <td>{{ $siswa->jurusan->nama_jurusan ?? '-' }}</td>
                                <td>
                                    @if($siswa->status_verifikasi === 'diverifikasi')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Terverifikasi
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $siswa->adminVerifikasi->name ?? '-' }}</td>
                                <td>{{ $siswa->verified_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($siswa->catatan_verifikasi, 50) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada riwayat verifikasi</h5>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection