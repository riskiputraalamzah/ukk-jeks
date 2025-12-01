@extends('layouts.admin')

@section('title', 'Detail Verifikasi - ' . $calonSiswa->nama_lengkap)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Detail Verifikasi</h1>
        <a href="{{ route('admin.verifikasi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="row">
        <!-- Data Pribadi -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-user me-2"></i>Data Pribadi
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Nama Lengkap</th>
                            <td>{{ $calonSiswa->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <th>NISN</th>
                            <td>{{ $calonSiswa->nisn ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>{{ $calonSiswa->jenis_kelamin }}</td>
                        </tr>
                        <tr>
                            <th>TTL</th>
                            <td>{{ $calonSiswa->tempat_lahir }}, {{ $calonSiswa->tanggal_lahir?->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Asal Sekolah</th>
                            <td>{{ $calonSiswa->asal_sekolah }}</td>
                        </tr>
                        <tr>
                            <th>No. HP</th>
                            <td>{{ $calonSiswa->no_hp }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Data Pendaftaran -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-file-alt me-2"></i>Data Pendaftaran
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">No. Pendaftaran</th>
                            <td>{{ $calonSiswa->nomor_pendaftaran ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jurusan</th>
                            <td>{{ $calonSiswa->jurusan->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ $calonSiswa->kelas->nama_kelas ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Gelombang</th>
                            <td>{{ $calonSiswa->gelombang->nama_gelombang ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status Bayar</th>
                            <td>
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Lunas
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Bayar</th>
                            <td>{{ $calonSiswa->pembayaran->tanggal_bayar?->format('d/m/Y H:i') ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Dokumen -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-warning text-dark">
            <i class="fas fa-file-pdf me-2"></i>Dokumen Pendaftaran
        </div>
        <div class="card-body">
            @if($calonSiswa->dokumen->count() > 0)
            <div class="row">
                @foreach($calonSiswa->dokumen as $dokumen)
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3">
                        <h6 class="mb-2">{{ $dokumen->jenis_dokumen }}</h6>
                        <a href="{{ Storage::url($dokumen->path_file) }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download me-1"></i>Download
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-muted">Tidak ada dokumen yang diupload.</p>
            @endif
        </div>
    </div>

    <!-- Aksi Verifikasi -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-check-circle me-2"></i>Aksi Verifikasi
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.verifikasi.approve', $calonSiswa->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan Verifikasi (Opsional)</label>
                            <textarea name="catatan" id="catatan" rows="3" class="form-control"
                                placeholder="Berikan catatan jika diperlukan..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-check me-2"></i>Verifikasi & Terima
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection