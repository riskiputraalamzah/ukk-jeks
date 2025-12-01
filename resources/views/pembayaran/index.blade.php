@extends('layouts.app')

@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-dark-800">Data Pembayaran</h1>
                <p class="text-dark-600">Riwayat dan status pembayaran Anda</p>
            </div>

            @if(auth()->user()->hasRole('admin'))
                <!-- Admin View -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Semua Pembayaran</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No. Transaksi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pembayarans as $pembayarans)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $pembayarans->kode_transaksi }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pembayarans->formulir->nama_lengkap }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($pembayarans->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                    @if($pembayarans->status == 'Lunas') bg-green-100 text-green-800
                                                    @elseif($pembayarans->status == 'Pending') bg-yellow-100 text-yellow-800
                                                    @elseif($pembayarans->status == 'Failed') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                {{ $pembayarans->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pembayarans->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('pembayaran.show', $pembayarans) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada data pembayaran
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $pembayarans->links() }}
                    </div>
                </div>
            @else
                <!-- User View -->
                @if($pembayarans->count() > 0)
                    <div class="grid gap-6">
                        @foreach($pembayarans as $p)
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $p->kode_transaksi }}</h3>
                                        <p class="text-gray-600 text-sm">Tanggal: {{ $p->created_at->format('d F Y H:i') }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-sm rounded-full 
                                                @if($p->status == 'Lunas') bg-green-100 text-green-800
                                                @elseif($p->status == 'Pending') bg-yellow-100 text-yellow-800
                                                @elseif($p->status == 'Failed') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                        {{ $p->status }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Jumlah</p>
                                        <p class="font-semibold text-gray-800">Rp {{ number_format($p->amount, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Metode</p>
                                        <p class="font-semibold text-gray-800">{{ $p->midtrans_payment_type ?? 'Midtrans' }}</p>
                                    </div>
                                    <!-- <div>
                                        <p class="text-sm text-gray-600">No. Referensi</p>
                                        <p class="font-semibold text-gray-800">{{ 
                                        $p->midtrans_transaction_id ?? '-' }}</p>
                                    </div> -->
                                </div>

                                <div class="flex justify-between items-center">
                                    <a href="{{ route('pembayaran.show', $p) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        Lihat Detail
                                    </a>
                                @if($p->status == 'Pending')
    <a href="{{ route('pembayaran.continue', $p) }}"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        Lanjutkan Pembayaran
    </a>
@endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $pembayarans->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-credit-card text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Pembayaran</h3>
                        <p class="text-gray-600 mb-6">Anda belum melakukan pembayaran pendaftaran.</p>
                        <a href="{{ route('pembayaran.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                            <i class="fas fa-credit-card mr-2"></i>
                            Bayar Sekarang
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection