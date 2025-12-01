@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Pembayaran Pendaftaran</h1>
                <p class="text-gray-600">Lakukan pembayaran untuk menyelesaikan pendaftaran</p>

                @if (session('error'))
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
                @endif
            </div>

            <!-- Informasi Biaya -->
            <div class="bg-blue-50 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-blue-800 mb-4">Detail Biaya</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Biaya Pendaftaran:</span>
                        <span class="font-medium">Rp {{ number_format($formulir->gelombang->harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-3">
                        <span class="font-semibold">Total yang harus dibayar:</span>
                        <span class="font-semibold text-green-600 text-lg" id="total-price">Rp {{ number_format($formulir->gelombang->harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Form Pembayaran -->
            <form action="{{ route('pembayaran.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data Siswa</label>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="font-medium text-gray-800">{{ $formulir->nama_lengkap }}</p>
                        <p class="text-sm text-gray-600">{{ $formulir->asal_sekolah }}</p>
                        <p class="text-sm text-gray-600">No. Formulir: {{ $formulir->nomor_pendaftaran }}</p>
                    </div>
                </div>

                <!-- Promo/Voucher Section (Optional) -->
                <div class="mb-6">
                    <label for="promo_voucher_id" class="block text-sm font-medium text-gray-700 mb-2">Kode Promo (Opsional)</label>
                    <input type="text"
                        name="promo_code"
                        id="promo_code"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masukkan kode promo jika ada">
                    <input type="hidden" name="promo_voucher_id" id="promo_voucher_id">
                </div>

                <!-- Metode Pembayaran Info -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-yellow-500 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-yellow-800 mb-1">Metode Pembayaran</h4>
                            <p class="text-yellow-700 text-sm">
                                Anda akan diarahkan ke halaman pembayaran Midtrans yang mendukung berbagai metode:
                                Kartu Kredit, Transfer Bank, E-Wallet, dan Convenience Store.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t pt-6">
                    <a href="{{ route('pembayaran.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-200 flex items-center">
                        <i class="fas fa-credit-card mr-2"></i>
                        Lanjutkan ke Pembayaran
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Metode Pembayaran -->
        <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
            <h3 class="font-semibold text-gray-800 mb-4">Metode Pembayaran yang Tersedia</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                    <i class="fas fa-credit-card text-blue-500 mr-3"></i>
                    <span>Kartu Kredit</span>
                </div>
                <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                    <i class="fas fa-university text-green-500 mr-3"></i>
                    <span>Transfer Bank</span>
                </div>
                <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                    <i class="fas fa-mobile-alt text-purple-500 mr-3"></i>
                    <span>E-Wallet</span>
                </div>
                <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                    <i class="fas fa-store text-orange-500 mr-3"></i>
                    <span>Minimarket</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const promoInput = document.getElementById('promo_code');
        const promoIdInput = document.getElementById('promo_voucher_id');
        const totalPriceElement = document.getElementById('total-price');

        // Create feedback element
        const feedbackElement = document.createElement('div');
        feedbackElement.className = 'mt-2 text-sm';
        promoInput.parentNode.appendChild(feedbackElement);

        let timeout = null;
        const basePrice = `{{$formulir->gelombang->harga}}`; // Harga dasar pendaftaran dari database

        promoInput.addEventListener('input', function() {
            clearTimeout(timeout);
            const code = this.value.trim();

            if (code === '') {
                resetPromo();
                return;
            }

            // Debounce 500ms
            timeout = setTimeout(() => {
                checkPromo(code);
            }, 500);
        });

        // Handle paste event specifically if needed, but input covers it.

        function checkPromo(code) {
            feedbackElement.textContent = 'Mengecek kode promo...';
            feedbackElement.className = 'mt-2 text-sm text-gray-500';

            fetch("{{ route('pembayaran.check-promo') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        kode_promo: code
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        feedbackElement.textContent = data.message;
                        feedbackElement.className = 'mt-2 text-sm text-green-600 font-medium';
                        promoIdInput.value = data.data.id;
                        updateTotal(data.data.nominal_potongan);
                    } else {
                        feedbackElement.textContent = data.message;
                        feedbackElement.className = 'mt-2 text-sm text-red-600';
                        promoIdInput.value = '';
                        resetTotal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    feedbackElement.textContent = 'Gagal mengecek kode promo.';
                    feedbackElement.className = 'mt-2 text-sm text-red-600';
                });
        }

        function updateTotal(discount) {
            const finalPrice = Math.max(0, basePrice - discount);
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            if (totalPriceElement) {
                totalPriceElement.textContent = formatter.format(finalPrice);
            }
        }

        function resetPromo() {
            feedbackElement.textContent = '';
            promoIdInput.value = '';
            resetTotal();
        }

        function resetTotal() {
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
            if (totalPriceElement) {
                totalPriceElement.textContent = formatter.format(basePrice);
            }
        }
    });
</script>
@endsection