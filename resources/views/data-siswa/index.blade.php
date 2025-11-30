@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: bold; color: #000000ff; margin-bottom: 8px;">
                        <i class="fas fa-user-graduate" style="margin-right: 10px;"></i>Data Siswa
                    </h2>
                    <p style="color: #000000ff; margin: 0;">Informasi data pribadi calon siswa</p>
                </div>
                <div style="text-align: right;">
                    <span style="background: #06b6d4; color: white; padding: 8px 12px; border-radius: 8px; font-size: 0.9rem;">
                        <i class="fas fa-id-card" style="margin-right: 5px;"></i>
                        {{ $formulir->nomor_pendaftaran ?? 'Belum ada' }}
                    </span>
                </div>
            </div>

            @if($formulir)
            <div class="row">
               <!-- Data Pribadi dengan Foto -->
<div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px; border: 1px solid #e5e7eb;">
    <div style="background: #f9fafb; padding: 15px; border-radius: 12px 12px 0 0; border-bottom: 1px solid #e5e7eb;">
        <h6 style="margin: 0; color: #1f2937; font-weight: 600;">
            <i class="fas fa-user" style="margin-right: 8px;"></i>Data Pribadi
        </h6>
    </div>
    <div style="padding: 25px;">
        <!-- Baris untuk Foto dan Info Utama -->
        <div class="row" style="margin-bottom: 25px;">
            <!-- Kolom Foto -->
            <div class="col-md-4" style="text-align: center;">
                @if($fotoSiswa)
                    <img src="{{ asset('storage/' . $fotoSiswa->path_file) }}" 
                         alt="Foto Siswa" 
                         style="max-width: 100%; height: 200px; object-fit: cover; border-radius: 8px; border: 2px solid #e5e7eb;">
                @else
                    <div style="background: #f9fafb; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px; height: 200px;">
                        <i class="fas fa-user" style="font-size: 2.5rem; color: #9ca3af; margin-bottom: 10px;"></i>
                        <span style="color: #9ca3af; font-size: 0.9rem;">Foto 3x4 belum diupload</span>
                    </div>
                @endif
            </div>
            
            <!-- Kolom Info Nama & Badges -->
             <div class="col-md-8">
                <h4 style="color: #1f2937; margin-bottom: 15px; font-weight: 600;">{{ $formulir->nama_lengkap }}</h4>

                

                <!-- <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 15px;">
                    <span style="background: #10b981; color: white; padding: 6px 10px; border-radius: 6px; font-size: 0.8rem;">
                        <i class="fas fa-graduation-cap" style="margin-right: 4px;"></i>
                        {{ $formulir->jurusan->nama ?? 'Belum dipilih' }}
                    </span> 
                    <span style="background: #f59e0b; color: #1f2937; padding: 6px 10px; border-radius: 6px; font-size: 0.8rem;">
                        <i class="fas fa-wave-square" style="margin-right: 4px;"></i>
                        {{ $formulir->gelombang->nama ?? 'Belum dipilih' }}
                    </span>
                </div>
                <div style="color: #6b7280; font-size: 0.9rem;">
                    <strong>No. Pendaftaran:</strong> {{ $formulir->nomor_pendaftaran ?? '-' }}
                </div> -->
            </div>
        </div>

        <!-- Baris untuk Detail Data Pribadi -->
        <div class="row">
            <div class="col-md-6" style="margin-bottom: 20px;">
                <label style="display: block; color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 5px;">NISN</label>
                <div style="font-weight: 600; color: #1f2937;">{{ $formulir->nisn ?? '-' }}</div>
            </div>
            <div class="col-md-6" style="margin-bottom: 20px;">
                <label style="display: block; color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 5px;">Jenis Kelamin</label>
                <div style="font-weight: 600; color: #1f2937;">
                    @if($formulir->jenis_kelamin == 'male')
                        Laki-laki
                    @elseif($formulir->jenis_kelamin == 'female')
                        Perempuan
                    @else
                        {{ $formulir->jenis_kelamin ?? '-' }}
                    @endif
                </div>
            </div>
            <div class="col-md-6" style="margin-bottom: 20px;">
                <label style="display: block; color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 5px;">Tempat Lahir</label>
                <div style="font-weight: 600; color: #1f2937;">{{ $formulir->tempat_lahir ?? '-' }}</div>
            </div>
            <div class="col-md-6" style="margin-bottom: 20px;">
                <label style="display: block; color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 5px;">Tanggal Lahir</label>
                <div style="font-weight: 600; color: #1f2937;">
                    {{ $formulir->tanggal_lahir ? $formulir->tanggal_lahir->format('d-m-Y') : '-' }}
                </div>
            </div>
            <div class="col-md-6" style="margin-bottom: 20px;">
                <label style="display: block; color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 5px;">Agama</label>
                <div style="font-weight: 600; color: #1f2937;">{{ $formulir->agama ?? '-' }}</div>
            </div>
            <div class="col-md-6" style="margin-bottom: 20px;">
                <label style="display: block; color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 5px;">NIK</label>
                <div style="font-weight: 600; color: #1f2937;">{{ $formulir->nik ?? '-' }}</div>
            </div>
            <div class="col-md-6" style="margin-bottom: 20px;">
                <label style="display: block; color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 5px;">Anak Ke</label>
                <div style="font-weight: 600; color: #1f2937;">{{ $formulir->anak_ke ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

                    <!-- Kontak & Alamat -->
                    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px; border: 1px solid #e5e7eb;">
                        <div style="background: #f9fafb; padding: 15px; border-radius: 12px 12px 0 0; border-bottom: 1px solid #e5e7eb;">
                            <h6 style="margin: 0; color: #1f2937; font-weight: 600;">
                                <i class="fas fa-address-book" style="margin-right: 8px;"></i>Kontak & Alamat
                            </h6>
                        </div>
                        <div style="padding: 25px;">
                            <div class="row">
                                <div class="col-md-6" style="margin-bottom: 20px;">
                                    <label style="display: block; color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 5px;">No. HP</label>
                                    <div style="font-weight: 600; color: #1f2937;">{{ $formulir->no_hp ?? '-' }}</div>
                                </div>
                                <div class="col-12" style="margin-bottom: 20px;">
                                    <label style="display: block; color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 5px;">Alamat Lengkap</label>
                                    <div style="font-weight: 600; color: #1f2937;">{{ $formulir->alamat ?? '-' }}</div>
                                </div>
                                <div class="col-md-4" style="margin-bottom: 20px;">
                                    <label style="display: block; color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 5px;">Desa/Kelurahan</label>
                                    <div style="font-weight: 600; color: #1f2937;">
                                        {{ $formulir->desa ?? '-' }}/{{ $formulir->kelurahan ?? '-' }}
                                    </div>
                                </div>
                                <div class="col-md-4" style="margin-bottom: 20px;">
                                    <label style="display: block; color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 5px;">Kecamatan</label>
                                    <div style="font-weight: 600; color: #1f2937;">{{ $formulir->kecamatan ?? '-' }}</div>
                                </div>
                                <div class="col-md-4" style="margin-bottom: 20px;">
                                    <label style="display: block; color: #6b7280; font-size: 0.875rem; font-weight: 500; margin-bottom: 5px;">Kota</label>
                                    <div style="font-weight: 600; color: #1f2937;">{{ $formulir->kota ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Asal Sekolah -->
                    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
                        <div style="background: #f9fafb; padding: 15px; border-radius: 12px 12px 0 0; border-bottom: 1px solid #e5e7eb;">
                            <h6 style="margin: 0; color: #1f2937; font-weight: 600;">
                                <i class="fas fa-school" style="margin-right: 8px;"></i>Asal Sekolah
                            </h6>
                        </div>
                        <div style="padding: 20px;">
                            <div style="font-weight: 600; color: #1f2937; font-size: 1.1rem;">{{ $formulir->asal_sekolah ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            @else
            <!-- State ketika belum ada data -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
                <div style="padding: 60px 20px; text-align: center;">
                    <i class="fas fa-user-slash" style="font-size: 3rem; color: #9ca3af; margin-bottom: 20px;"></i>
                    <h4 style="color: #9ca3af; margin-bottom: 15px;">Belum ada data formulir pendaftaran</h4>
                    <p style="color: #9ca3af; margin-bottom: 25px;">Silakan lengkapi formulir pendaftaran terlebih dahulu untuk melihat data siswa.</p>
                    <a href="{{ route('formulir.index') }}" style="background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; font-weight: 500;">
                        <i class="fas fa-edit" style="margin-right: 8px;"></i>Isi Formulir Pendaftaran
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection