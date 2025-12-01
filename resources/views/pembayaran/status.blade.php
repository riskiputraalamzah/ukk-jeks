@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-center mb-6">
                @if($status === 'success')
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                        <i class="fas fa-check text-green-600 text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-green-600">Pembayaran Berhasil!</h1>
                @elseif($status === 'pending')
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-4">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-yellow-600">Menunggu Pembayaran</h1>
                @else
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-times text-red-600 text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-red-600">Pembayaran Gagal</h1>
                @endif
                <p class="text-gray-600 mt-2">{{ $message }}</p>
            </div>

            <!-- Informasi Transaksi -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Detail Transaksi</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>No. Transaksi:</span>
                        <span class="font-medium">{{ $pembayaran->kode_transaksi }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Nama Siswa:</span>
                        <span class="font-medium">{{ $pembayaran->formulir->nama_lengkap }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Jumlah Pembayaran:</span>
                        <span class="font-medium text-green-600">
                            Rp {{ number_format($pembayaran->jumlah_akhir, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Status Pembayaran:</span>
                        <span class="font-medium">
                            @if($pembayaran->status === 'Lunas')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">LUNAS</span>
                            @elseif($pembayaran->status === 'Pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">PENDING</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-sm">{{ strtoupper($pembayaran->status) }}</span>
                            @endif
                        </span>
                    </div>
                    @if($pembayaran->tanggal_bayar)
                    <div class="flex justify-between">
                        <span>Tanggal Bayar:</span>
                        <span class="font-medium">{{ $pembayaran->tanggal_bayar->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @if($status === 'success')
                    <a href="{{ route('pembayaran.show', $pembayaran) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-receipt mr-2"></i> Lihat Detail Pembayaran
                    </a>
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-home mr-2"></i> Kembali ke Dashboard
                    </a>
                @elseif($status === 'pending')
                    <a href="{{ route('pembayaran.continue', $pembayaran) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition">
                        <i class="fas fa-redo mr-2"></i> Lanjutkan Pembayaran
                    </a>
                    <a href="{{ route('pembayaran.show', $pembayaran) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-receipt mr-2"></i> Lihat Detail
                    </a>
                @else
                    <a href="{{ route('pembayaran.continue', $pembayaran) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-redo mr-2"></i> Coba Lagi
                    </a>
                    <a href="{{ route('pembayaran.create') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i> Buat Pembayaran Baru
                    </a>
                @endif
            </div>

            <!-- Instruksi -->
            @if($status === 'success')
            <div class="mt-8 p-4 bg-green-50 rounded-lg border border-green-200">
                <h4 class="font-semibold text-green-800 mb-2">Selanjutnya:</h4>
                <ul class="text-sm text-green-700 space-y-1">
                    <li>• Pembayaran Anda sudah terkonfirmasi</li>
                    <li>• Proses pendaftaran akan dilanjutkan oleh admin</li>
                    <li>• Anda akan mendapatkan notifikasi via email/WA</li>
                    <li>• Simpan bukti pembayaran ini untuk referensi</li>
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection