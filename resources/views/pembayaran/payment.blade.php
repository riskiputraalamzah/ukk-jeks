@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Pembayaran Pendaftaran</h1>
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
                            <span class="font-medium text-green-600">Rp {{ number_format($pembayaran->jumlah_akhir, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="font-semibold">Total:</span>
                            <span class="font-semibold text-green-600">Rp {{ number_format($pembayaran->jumlah_akhir, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Midtrans Payment Gateway -->
                <div>
                    <h3 class="font-semibold text-gray-800 mb-4">Pilih Metode Pembayaran</h3>
                    <div id="midtrans-payment">
                        <!-- Snap.js will render the payment form here -->
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-6 bg-blue-50 rounded-lg p-4">
                <h4 class="font-semibold text-blue-800 mb-2">Instruksi Pembayaran:</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Pilih metode pembayaran yang diinginkan</li>
                    <li>• Ikuti instruksi pada halaman pembayaran</li>
                    <li>• Pembayaran akan diverifikasi otomatis</li>
                    <li>• Simpan bukti pembayaran Anda</li>
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
        onSuccess: function(result){
            console.log('success', result);
            window.location.href = "{{ route('pembayaran.status') }}?order_id={{ $pembayaran->midtrans_order_id }}";
        },
        onPending: function(result){
            console.log('pending', result);
            window.location.href = "{{ route('pembayaran.status') }}?order_id={{ $pembayaran->midtrans_order_id }}";
        },
        onError: function(result){
            console.log('error', result);
            alert('Pembayaran gagal, silakan coba lagi.');
        },
        onClose: function(){
            console.log('customer closed the popup without finishing the payment');
        }
    });
</script>
@endsection