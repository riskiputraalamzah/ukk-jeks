<div class="py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <!-- Icon -->
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-lock text-red-500 text-2xl"></i>
            </div>
            
            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Akses Dibatasi</h2>
            
            <!-- Message -->
            <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                {{ $message }}
            </p>
            
            <!-- Progress Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 max-w-md mx-auto">
                <h4 class="font-semibold text-blue-800 mb-2">Urutan Pendaftaran:</h4>
                <ol class="text-sm text-blue-700 text-left space-y-1">
                    <li>1. Isi Formulir Pendaftaran</li>
                    <li>2. Upload Dokumen</li>
                    <li>3. Isi Data Orang Tua & Wali</li>
                    <li>4. Lihat Data Siswa</li>
                    <li>5. Lakukan Pembayaran</li>
                </ol>
            </div>
            
            <!-- Action Button -->
            <a href="{{ $route }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium text-lg">
                <i class="fas fa-arrow-right mr-2"></i>
                {{ $buttonText }}
            </a>
            
            <!-- Back to Dashboard -->
            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>