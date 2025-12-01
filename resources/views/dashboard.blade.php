@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600 mt-2">Portal Pendaftaran Peserta Didik Baru</p>
        </div>

        <!-- Status Pendaftaran -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Status Pendaftaran</h2>
                    <p class="text-gray-500 text-sm">Status formulir pendaftaran</p>
                </div>
            </div>

            @php
                $form = auth()->user()->formulir;
                $status = $form ? 'terisi' : 'belum';
                
                // Hitung dokumen yang sudah diupload
                $dokumenCount = 0;
                if ($form) {
                    try {
                        $dokumenCount = \App\Models\DokumenPendaftaran::where('formulir_id', $form->id)->count();
                    } catch (\Exception $e) {
                        $dokumenCount = 0;
                    }
                }

                // Cek data orang tua & wali
                $hasOrangTua = $form && $form->orangTua;
                $hasWali = $form && $form->wali;
                $keluargaComplete = $hasOrangTua || $hasWali;

                // Status pembayaran
                $pembayaran = auth()->user()->pembayaran;
                $isPaid = $pembayaran && ($pembayaran->status === 'Lunas' || $pembayaran->midtrans_status === 'settlement');
                $isPending = $pembayaran && $pembayaran->status === 'Pending';

                // Status verifikasi
                $isVerified = $form && $form->status_verifikasi == 'verified';
            @endphp

            <div class="mb-6">
                @if ($status === 'belum')
                    <div class="inline-flex items-center px-4 py-3 bg-red-100 border border-red-200 text-red-700 rounded-xl font-semibold">
                        <i class="fas fa-clock mr-2"></i>
                        Belum Mengisi Formulir
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('formulir.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                            <i class="fas fa-file-alt mr-2"></i>
                            Isi Formulir Pendaftaran
                        </a>
                    </div>
                @else
                    <div class="inline-flex items-center px-4 py-3 bg-green-100 border border-green-200 text-green-700 rounded-xl font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>
                        Sudah Mengisi Formulir
                    </div>

                    <div class="inline-flex items-center px-4 py-3 bg-green-100 border border-green-200 text-green-700 rounded-xl font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>
                        Sudah Mengisi Upload Dokumen ({{ $dokumenCount }} berkas)
                    </div>

                    <div class="inline-flex items-center px-4 py-3 bg-green-100 border border-green-200 text-green-700 rounded-xl font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>
                        Sudah Mengisi Data Orang Tua & Wali
                    </div>

                    <div class="mt-4 space-y-4">
                        <div>
                            <a href="{{ route('formulir.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Formulir Pendaftaran
                            </a>

                             <a href="{{ route('dokumen.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Upload Dokumen
                            </a>

                            <a href="{{ route('data-keluarga.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Data Orang tua & Wali
                            </a>
                        </div>
                        
                        <!-- Info Ringkasan Data -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-2">Ringkasan Data:</h4>
                            <p class="text-sm text-gray-600"><strong>Nama:</strong> {{ $form->nama_lengkap }}</p>
                            <p class="text-sm text-gray-600"><strong>Asal Sekolah:</strong> {{ $form->asal_sekolah }}</p>
                            <p class="text-sm text-gray-600"><strong>No. Pendaftaran:</strong> {{ $form->nomor_pendaftaran ?? 'Belum ada' }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Progress Pendaftaran - HORIZONTAL SIMPLE -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Progress Pendaftaran</h2>
            
            <!-- Horizontal Progress -->
            <div class="flex flex-col space-y-4">
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    @php
                        $completedSteps = 0;
                        if ($form) $completedSteps++;
                        if ($dokumenCount > 0) $completedSteps++;
                        if ($keluargaComplete) $completedSteps++;
                        if ($isPaid) $completedSteps++;
                        if ($isVerified) $completedSteps++;
                        $progressPercentage = ($completedSteps / 5) * 100;
                    @endphp
                    <div class="bg-green-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                </div>

                <!-- Steps Horizontal -->
                <div class="flex justify-between items-start">
                    <!-- Step 1: Formulir -->
                    <div class="text-center flex-1">
                        <div class="w-8 h-8 {{ $form ? 'bg-green-500' : 'bg-gray-400' }} rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-file-alt text-white text-xs"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800 text-xs mb-1">Formulir</h3>
                        <p class="text-xs {{ $form ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $form ? '✓' : '✗' }}
                        </p>
                    </div>

                    <!-- Step 2: Dokumen -->
                    <div class="text-center flex-1">
                        <div class="w-8 h-8 {{ $dokumenCount > 0 ? 'bg-green-500' : 'bg-gray-400' }} rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-file-upload text-white text-xs"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800 text-xs mb-1">Dokumen</h3>
                        <p class="text-xs {{ $dokumenCount > 0 ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $dokumenCount > 0 ? '✓' : '✗' }}
                        </p>
                    </div>

                    <!-- Step 3: Keluarga -->
                    <div class="text-center flex-1">
                        <div class="w-8 h-8 {{ $keluargaComplete ? 'bg-green-500' : 'bg-gray-400' }} rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-users text-white text-xs"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800 text-xs mb-1">Keluarga</h3>
                        <p class="text-xs {{ $keluargaComplete ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $keluargaComplete ? '✓' : '✗' }}
                        </p>
                    </div>

                    <!-- Step 4: Pembayaran -->
                    <div class="text-center flex-1">
                        <div class="w-8 h-8 {{ $isPaid ? 'bg-green-500' : ($isPending ? 'bg-yellow-500' : 'bg-gray-400') }} rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-credit-card text-white text-xs"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800 text-xs mb-1">Pembayaran</h3>
                        <p class="text-xs {{ $isPaid ? 'text-green-600' : ($isPending ? 'text-yellow-600' : 'text-gray-500') }}">
                            {{ $isPaid ? '✓' : ($isPending ? '...' : '✗') }}
                        </p>
                    </div>

                    <!-- Step 5: Verifikasi -->
                    <div class="text-center flex-1">
                        <div class="w-8 h-8 {{ $isVerified ? 'bg-green-500' : 'bg-gray-400' }} rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-check-double text-white text-xs"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800 text-xs mb-1">Verifikasi</h3>
                        <p class="text-xs {{ $isVerified ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $isVerified ? '✓' : '✗' }}
                        </p>
                    </div>
                </div>

                <!-- Progress Text -->
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold text-green-600">{{ $completedSteps }}/5</span> langkah selesai
                        <span class="mx-2">•</span>
                        {{ $progressPercentage }}% lengkap
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Aksi Cepat</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('formulir.index') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-200 transition duration-200 text-center group">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-600 transition duration-200">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1 text-sm">Formulir</h3>
                    <p class="text-sm text-gray-600">Isi/Edit data</p>
                </a>

                <a href="{{ route('dokumen.index') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-200 transition duration-200 text-center group">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-green-600 transition duration-200">
                        <i class="fas fa-upload text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1 text-sm">Upload Dokumen</h3>
                    <p class="text-sm text-gray-600">Upload berkas</p>
                </a>

                 <a href="{{ route('data-keluarga.index') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-200 transition duration-200 text-center group">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-green-600 transition duration-200">
                        <i class="fas fa-upload text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1 text-sm">Data Orang Tua & Wali</h3>
                    <p class="text-sm text-gray-600">Upload berkas</p>
                </a>

                <a href="{{ route('pembayaran.create') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-200 transition duration-200 text-center group">
                    <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-600 transition duration-200">
                        <i class="fas fa-credit-card text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1 text-sm">Pembayaran</h3>
                    <p class="text-sm text-gray-600">Bayar sekarang</p>
                </a>

                <!-- <a href="#" class="p-4 border border-gray-200 rounded-lg hover:bg-orange-50 hover:border-orange-200 transition duration-200 text-center group">
                    <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-orange-600 transition duration-200">
                        <i class="fas fa-question-circle text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1 text-sm">Bantuan</h3>
                    <p class="text-sm text-gray-600">Hubungi admin</p>
                </a> -->
            </div>
        </div>

        <!-- Pengumuman -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mt-6">
            <div class="flex items-start">
                <i class="fas fa-bullhorn text-yellow-500 text-xl mr-3 mt-1"></i>
                <div>
                    <h3 class="font-semibold text-yellow-800 mb-2">Pengumuman</h3>
                    <p class="text-yellow-700 text-sm">
                        Pendaftaran PPDB Tahun Ajaran 2024/2025 sedang berlangsung. 
                        Pastikan Anda melengkapi semua data dan dokumen sebelum tanggal penutupan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection