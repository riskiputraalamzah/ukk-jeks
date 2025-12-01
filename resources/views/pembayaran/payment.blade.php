@extends('layouts.app')

@section('content')
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">
                        @if(isset($continue) && $continue)
                            Lanjutkan Pembayaran
                        @else
                            Pembayaran Pendaftaran
                        @endif
                    </h1>
                    <p class="text-gray-600">Selesaikan pembayaran Anda</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informasi Pembayaran -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Detail Pembayaran</h3>
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
                                <span>Jumlah:</span>
                                <span class="font-medium text-green-600">Rp
                                    {{ number_format($pembayaran->jumlah_akhir, 0, ',', '.') }}</span>
                            </div>
                            @if($pembayaran->midtrans_payment_type)
                                <div class="flex justify-between">
                                    <span>Metode Terpilih:</span>
                                    <span class="font-medium capitalize">{{ $pembayaran->midtrans_payment_type }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between border-t pt-3">
                                <span class="font-semibold">Total:</span>
                                <span class="font-semibold text-green-600">Rp
                                    {{ number_format($pembayaran->jumlah_akhir, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if(isset($continue) && $continue)
                            <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
                                <p class="text-sm text-yellow-700">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Melanjutkan pembayaran yang tertunda
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Midtrans Payment Gateway -->
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4">Pilih Metode Pembayaran</h3>
                        <div id="midtrans-payment">
                            <!-- Snap.js will render the payment form here -->
                        </div>

                        <div class="mt-4 text-center">
                            <a href="{{ route('pembayaran.show', $pembayaran) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali ke Detail Pembayaran
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="mt-6 bg-blue-50 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-800 mb-2">Instruksi Pembayaran:</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Pilih metode pembayaran yang diinginkan</li>
                        <li>• Ikuti instruksi pada halaman pembayaran</li>
                        <li>• Jangan tutup halaman selama proses pembayaran</li>
                        <li>• Simpan bukti pembayaran Anda</li>
                        <li>• Status akan otomatis terupdate setelah pembayaran berhasil</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap.js -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        // Render Midtrans Snap
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) {
                console.log('success', result);
                // Redirect ke callback dengan payment_type
                window.location.href = "{{ route('pembayaran.callback') }}?" +
                    "order_id={{ $pembayaran->kode_transaksi }}" +
                    "&status=success" +
                    "&payment_type=" + encodeURIComponent(result.payment_type);
            },
            onPending: function (result) {
                console.log('pending', result);
                // Kirim data ke notification endpoint untuk menyimpan payment_type
                fetch("{{ route('pembayaran.notification') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_id: '{{ $pembayaran->kode_transaksi }}',
                        transaction_status: 'pending',
                        payment_type: result.payment_type,
                        transaction_id: result.transaction_id,
                        fraud_status: 'accept'
                    })
                }).then(() => {
                    // Redirect dengan payment_type
                    window.location.href = "{{ route('pembayaran.callback') }}?" +
                        "order_id={{ $pembayaran->kode_transaksi }}" +
                        "&status=pending" +
                        "&payment_type=" + encodeURIComponent(result.payment_type);
                }).catch((error) => {
                    // Jika fetch gagal, tetap redirect tanpa payment_type
                    console.error('Error saving payment method:', error);
                    window.location.href = "{{ route('pembayaran.callback') }}?" +
                        "order_id={{ $pembayaran->kode_transaksi }}&status=pending";
                });
            },
            onError: function (result) {
                console.log('error', result);
                // Coba simpan payment method jika ada
                if (result.payment_type) {
                    fetch("{{ route('pembayaran.notification') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: '{{ $pembayaran->kode_transaksi }}',
                            transaction_status: 'cancel',
                            payment_type: result.payment_type
                        })
                    });
                }

                // Redirect dengan payment_type jika ada
                let url = "{{ route('pembayaran.callback') }}?order_id={{ $pembayaran->kode_transaksi }}&status=error";
                if (result.payment_type) {
                    url += "&payment_type=" + encodeURIComponent(result.payment_type);
                }
                window.location.href = url;
            },
            onClose: function () {
                console.log('customer closed the popup without finishing the payment');
                alert('Anda dapat melanjutkan pembayaran kapan saja melalui halaman detail pembayaran.');
            }
        });
    </script>
@endsection