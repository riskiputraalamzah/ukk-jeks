@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Status -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 text-center">
            <div class="mb-4">
                @if(isset($formulir->status_verifikasi) && $formulir->status_verifikasi === 'diverifikasi')
                <span
                    class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                    <i class="fas fa-check-circle mr-2"></i>TERVERIFIKASI
                </span>
                @else
                <span
                    class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                    <i class="fas fa-clock mr-2"></i>MENUNGGU VERIFIKASI
                </span>
                @endif
            </div>

            <h1 class="text-2xl font-bold text-gray-800">
                @if(isset($formulir->nomor_pendaftaran))
                Nomor Pendaftaran: {{ $formulir->nomor_pendaftaran }}
                @else
                Nomor Pendaftaran: Belum ada
                @endif
            </h1>
            <p class="text-gray-600 mt-2">Tanggal Pendaftaran: {{ $formulir->created_at->format('d F Y') }}</p>
        </div>

        <!-- Progress Checklist -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Progress Pendaftaran</h2>

            <div class="space-y-4">
                @foreach($progress as $key => $step)
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center 
                                        {{ $step['completed'] ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                        @if($step['completed'])
                        <i class="fas fa-check text-xs"></i>
                        @else
                        <span class="text-sm">{{ $loop->iteration }}</span>
                        @endif
                    </div>

                    <div class="ml-4 flex-1">
                        <div class="flex justify-between items-center">
                            <span class="font-medium {{ $step['completed'] ? 'text-green-600' : 'text-gray-600' }}">
                                {{ $step['label'] }}
                            </span>
                            @if(isset($step['status']))
                            <span
                                class="text-sm px-2 py-1 rounded-full 
                                                        {{ $step['status'] === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($step['status']) }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Status Pembayaran -->
        @if($pembayaran)
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Status Pembayaran</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="font-medium">Status:</span>
                    <span
                        class="ml-2 px-3 py-1 rounded-full text-sm 
                                        {{ $pembayaran->status === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ strtoupper($pembayaran->status) }}
                    </span>
                </div>
                <div>
                    <span class="font-medium">Total:</span>
                    <span class="ml-2">Rp {{ number_format($pembayaran->jumlah_akhir, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="font-medium">Metode:</span>
                    <span class="ml-2">{{ $pembayaran->metode ?? 'Transfer Bank' }}</span>
                </div>
                @if($pembayaran->admin_verifikasi_id_user)
                <div>
                    <span class="font-medium">Diverifikasi oleh:</span>
                    <span class="ml-2">Admin</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Tindakan Selanjutnya</h2>

            <div class="flex flex-wrap gap-4">
                @if(!$pembayaran)
                <a href="{{ route('pembayaran.create') }}"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                    <i class="fas fa-credit-card mr-2"></i>Bayar Sekarang
                </a>
                @elseif($formulir && $formulir->status_verifikasi === 'diverifikasi')
                <a href="{{ route('status.cetak-pdf', $formulir->id) }}"
                    class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-medium">
                    <i class="fas fa-print mr-2"></i>Cetak PDF Bukti Pendaftaran
                </a>
                @elseif($pembayaran && $pembayaran->status === 'lunas' || $pembayaran && $pembayaran->status === 'menunggu_verifikasi')
                <button class="px-6 py-3 bg-gray-400 text-white rounded-lg font-medium cursor-not-allowed">
                    <i class="fas fa-clock mr-2"></i>Tunggu Verifikasi Admin
                </button>
                @endif

                <!-- <a href="{{ route('dokumen.index') }}" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200 font-medium">
                            <i class="fas fa-upload mr-2"></i>Upload Dokumen
                        </a>

                        <a href="{{ route('data-keluarga.index') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200 font-medium">
                            <i class="fas fa-users mr-2"></i>Data Orang Tua
                        </a> -->
            </div>

            <!-- Informasi Setelah Cetak PDF -->
            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    Setelah mencetak PDF bukti pendaftaran, silakan bawa ke sekolah untuk:
                    <br>- Pengambilan seragam
                    <br>- Informasi kelas dan jadwal
                </p>
            </div>
        </div>
    </div>
</div>
@endsection