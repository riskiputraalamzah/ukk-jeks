@extends('layouts.app')

@section('title', 'Detail Pembayaran - ' . $pembayaran->kode_transaksi)

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 mb-2">Detail Pembayaran</h1>
                        <p class="text-gray-600">Kode Transaksi: 
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $pembayaran->kode_transaksi }}</span>
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            {{ $pembayaran->status == 'Lunas' ? 'bg-green-100 text-green-800' : 
                               ($pembayaran->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                               'bg-red-100 text-red-800') }}">
                            {{ $pembayaran->status }}
                        </span>
                    </div>
                </div>

                <!-- Informasi Utama -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Informasi Pembayaran -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h3 class="font-semibold text-gray-700 mb-4 text-lg flex items-center">
                            <i class="fas fa-credit-card mr-2 text-blue-600"></i>
                            Informasi Pembayaran
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Kode Transaksi:</span>
                                <span class="font-medium font-mono">{{ $pembayaran->kode_transaksi }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium capitalize">{{ $pembayaran->status }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Metode Bayar:</span>
                                <span class="font-medium">{{ $pembayaran->metode_bayar ?? 'Belum dipilih' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Tanggal Bayar:</span>
                                <span class="font-medium">{{  $pembayaran->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($pembayaran->no_kuitansi)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">No Kuitansi:</span>
                                <span class="font-medium font-mono">{{ $pembayaran->no_kuitansi }}</span>
                            </div>
                            @endif
                        </div>
                     </div>

                    <!-- Detail Biaya -->
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h3 class="font-semibold text-gray-700 mb-4 text-lg flex items-center">
                            <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>
                            Detail Biaya
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Jumlah Awal:</span>
                                <span class="font-medium">Rp {{ number_format($pembayaran->jumlah_awal, 0, ',', '.') }}</span>
                            </div>
                            @if($pembayaran->promo)
                            <div class="flex justify-between items-center text-red-600">
                                <span>Diskon/Promo:</span>
                                <span class="font-medium">- Rp {{ number_format($pembayaran->jumlah_awal - $pembayaran->jumlah_akhir, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center border-t border-gray-200 pt-3">
                                <span class="text-gray-800 font-semibold">Total Bayar:</span>
                                <span class="text-lg font-bold text-green-600">Rp {{ number_format($pembayaran->jumlah_akhir, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Informasi Midtrans -->
                    @if($pembayaran->midtrans_order_id)
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h3 class="font-semibold text-gray-700 mb-4 text-lg flex items-center">
                            <i class="fas fa-exchange-alt mr-2 text-purple-600"></i>
                            Informasi Midtrans
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Order ID:</span>
                                <span class="font-medium font-mono text-sm">{{ $pembayaran->midtrans_order_id }}</span>
                            </div>
                            @if($pembayaran->midtrans_transaction_id)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Transaction ID:</span>
                                <span class="font-medium font-mono text-sm">{{ $pembayaran->midtrans_transaction_id }}</span>
                            </div>
                            @endif
                            @if($pembayaran->midtrans_payment_type)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Payment Type:</span>
                                <span class="font-medium capitalize">{{ $pembayaran->midtrans_payment_type }}</span>
                            </div>
                            @endif
                            @if($pembayaran->midtrans_status)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Status Midtrans:</span>
                                <span class="font-medium capitalize">{{ $pembayaran->midtrans_status }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Informasi Verifikasi -->
                    @if($pembayaran->verified_at)
                    <div class="border border-gray-200 rounded-lg p-6">
                        <h3 class="font-semibold text-gray-700 mb-4 text-lg flex items-center">
                            <i class="fas fa-user-check mr-2 text-blue-600"></i>
                            Informasi Verifikasi
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Status Verifikasi:</span>
                                <span class="font-medium text-green-600">Terverifikasi</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Tanggal Verifikasi:</span>
                                <span class="font-medium">{{ $pembayaran->verified_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($pembayaran->adminVerifikasi)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Admin Verifikasi:</span>
                                <span class="font-medium">{{ $pembayaran->adminVerifikasi->name }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Bukti Bayar -->
                @if($pembayaran->bukti_bayar)
                <div class="border border-gray-200 rounded-lg p-6 mb-8">
                    <h3 class="font-semibold text-gray-700 mb-4 text-lg flex items-center">
                        <i class="fas fa-receipt mr-2 text-orange-600"></i>
                        Bukti Pembayaran
                    </h3>
                    <div class="flex flex-col items-center">
                        <img src="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" 
                             alt="Bukti Bayar" 
                             class="max-w-md rounded-lg shadow-md mb-4">
                        <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" 
                           target="_blank" 
                           class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Lihat Gambar Ukuran Penuh
                        </a>
                    </div>
                </div>
                @endif

                <!-- Catatan -->
                @if($pembayaran->catatan)
                <div class="border border-gray-200 rounded-lg p-6 mb-8">
                    <h3 class="font-semibold text-gray-700 mb-4 text-lg flex items-center">
                        <i class="fas fa-sticky-note mr-2 text-yellow-600"></i>
                        Catatan
                    </h3>
                    <p class="text-gray-700 bg-yellow-50 p-4 rounded-lg">{{ $pembayaran->catatan }}</p>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <a href="{{ route('pembayaran.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-700 text-gray py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Daftar Pembayaran
                    </a>
                    
                    @if(auth()->user()->hasRole('admin') && $pembayaran->status != 'Lunas')
                    <button onclick="openVerificationModal()" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Verifikasi Pembayaran
                    </button>
                    @endif

                    @if($pembayaran->path_nota_pdf)
                    <a href="{{ asset('storage/' . $pembayaran->path_nota_pdf) }}" 
                       target="_blank" 
                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Download Nota PDF
                    </a>
                    @endif
                </div>

                <!-- Timeline Status -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="font-semibold text-gray-700 mb-4 text-lg flex items-center">
                        <i class="fas fa-history mr-2 text-purple-600"></i>
                        Timeline Status
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-4"></div>
                            <div>
                                <p class="font-medium">Transaksi Dibuat</p>
                                <p class="text-sm text-gray-500">{{ $pembayaran->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($pembayaran->tanggal_bayar)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-4"></div>
                            <div>
                                <p class="font-medium">Pembayaran Dilakukan</p>
                                <p class="text-sm text-gray-500">{{ $pembayaran->tanggal_bayar->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($pembayaran->verified_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-500 rounded-full mr-4"></div>
                            <div>
                                <p class="font-medium">Pembayaran Diverifikasi</p>
                                <p class="text-sm text-gray-500">{{ $pembayaran->verified_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi (Untuk Admin) -->
@if(auth()->user()->hasRole('admin') && $pembayaran->status != 'Lunas')
<div id="verificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Verifikasi Pembayaran</h3>
            
            <form action="{{ route('pembayaran.verify', $pembayaran) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Lunas">Lunas</option>
                        <option value="Menunggu">Menunggu</option>
                        <option value="Pending">Pending</option>
                        <option value="Failed">Failed</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Catatan (Opsional):</label>
                    <textarea name="catatan" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeVerificationModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openVerificationModal() {
        document.getElementById('verificationModal').classList.remove('hidden');
    }
    
    function closeVerificationModal() {
        document.getElementById('verificationModal').classList.add('hidden');
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('verificationModal');
        if (event.target === modal) {
            closeVerificationModal();
        }
    }
</script>
@endif

@endsection