@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Data Keluarga</h1>
            <p class="text-gray-600 mt-2">Pilih dan lengkapi data orang tua atau wali calon siswa</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span class="text-green-700 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                    <span class="text-red-700 font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                    <div>
                        <span class="text-red-700 font-medium">Terjadi kesalahan:</span>
                        <ul class="mt-1 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Pilihan Tipe Data - Hanya tampil jika belum memilih -->
        @if(!$selectedType)
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Pilih Jenis Data Keluarga</h2>
            <p class="text-gray-600 mb-6">Silakan pilih salah satu opsi di bawah ini:</p>
            
            <form action="{{ route('data-keluarga.select-type') }}" method="POST" id="typeForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pilihan Orang Tua -->
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="orang_tua" class="hidden" onchange="this.form.submit()">
                        <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-blue-500 hover:bg-blue-50 transition duration-200">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-blue-500 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Data Orang Tua Kandung</h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    Isi data lengkap ayah dan ibu kandung
                                </p>
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    Direkomendasikan
                                </span>
                            </div>
                        </div>
                    </label>

                    <!-- Pilihan Wali -->
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="wali" class="hidden" onchange="this.form.submit()">
                        <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-green-500 hover:bg-green-50 transition duration-200">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-user-tie text-green-500 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Data Wali</h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    Jika diasuh oleh wali (bukan orang tua kandung)
                                </p>
                                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                    Opsional
                                </span>
                            </div>
                        </div>
                    </label>
                </div>
            </form>
        </div>
        @endif

        <!-- Form Orang Tua Combined -->
        @if($selectedType == 'orang_tua')
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <!-- Header dengan tombol kembali -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <!-- <a href="{{ route('data-keluarga.reset-type') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Pilihan
                    </a> -->
                    <h2 class="text-xl font-bold text-gray-800">Data Orang Tua Kandung</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ ($orangTua && $orangTua->nama_ayah && $orangTua->nama_ibu) ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        <i class="fas {{ ($orangTua && $orangTua->nama_ayah && $orangTua->nama_ibu) ? 'fa-check' : 'fa-clock' }} mr-1"></i>
                        {{ ($orangTua && $orangTua->nama_ayah && $orangTua->nama_ibu) ? 'Lengkap' : 'Belum Lengkap' }}
                    </span>
                    @if($orangTua)
                    <form action="{{ route('data-keluarga.delete') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data orang tua?')">
                        @csrf
                        <input type="hidden" name="formulir_id" value="{{ $formulir->id }}">
                        <input type="hidden" name="type" value="orang_tua">
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i>Hapus Data
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <form action="{{ route('data-keluarga.store-combined') }}" method="POST">
                @csrf
                <input type="hidden" name="formulir_id" value="{{ $formulir->id }}">

                <!-- Section Ayah -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-male text-blue-500 mr-2"></i>
                        Data Ayah Kandung
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Ayah -->
                        <div class="md:col-span-2">
                            <label for="nama_ayah" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap Ayah *</label>
                            <input type="text" id="nama_ayah" name="ayah[nama]" 
                                   value="{{ old('ayah.nama', $orangTua->nama_ayah ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   required>
                            @error('ayah.nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir Ayah -->
                        <div>
                            <label for="tanggal_lahir_ayah" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir Ayah</label>
                            <input type="date" id="tanggal_lahir_ayah" name="ayah[tanggal_lahir]" 
                                   value="{{ old('ayah.tanggal_lahir', $orangTua && $orangTua->tanggal_lahir_ayah ? \Carbon\Carbon::parse($orangTua->tanggal_lahir_ayah)->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- Pekerjaan Ayah -->
                        <div>
                            <label for="pekerjaan_ayah" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ayah</label>
                            <input type="text" id="pekerjaan_ayah" name="ayah[pekerjaan]" 
                                   value="{{ old('ayah.pekerjaan', $orangTua->pekerjaan_ayah ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- Penghasilan Ayah -->
                        <div>
                            <label for="penghasilan_ayah" class="block text-sm font-medium text-gray-700 mb-2">Penghasilan Ayah (per bulan)</label>
                            <input type="number" id="penghasilan_ayah" name="ayah[penghasilan]" step="0.01" min="0"
                                   value="{{ old('ayah.penghasilan', $orangTua->penghasilan_ayah ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- NIK Ayah -->
                        <div>
                            <label for="nik_ayah" class="block text-sm font-medium text-gray-700 mb-2">NIK Ayah</label>
                            <input type="text" id="nik_ayah" name="ayah[nik]" 
                                   value="{{ old('ayah.nik', $orangTua->nik_ayah ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- No HP Ayah -->
                        <div class="md:col-span-2">
                            <label for="no_hp_ayah" class="block text-sm font-medium text-gray-700 mb-2">No. HP Ayah *</label>
                            <input type="text" id="no_hp_ayah" name="ayah[no_hp]" 
                                   value="{{ old('ayah.no_hp', $orangTua->no_hp_ayah ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   required>
                            @error('ayah.no_hp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Ayah -->
                        <div class="md:col-span-2">
                            <label for="alamat_ayah" class="block text-sm font-medium text-gray-700 mb-2">Alamat Ayah *</label>
                            <textarea id="alamat_ayah" name="ayah[alamat]" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                      required>{{ old('ayah.alamat', $orangTua->alamat_ayah ?? '') }}</textarea>
                            @error('ayah.alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section Ibu -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-female text-pink-500 mr-2"></i>
                        Data Ibu Kandung
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Ibu -->
                        <div class="md:col-span-2">
                            <label for="nama_ibu" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap Ibu *</label>
                            <input type="text" id="nama_ibu" name="ibu[nama]" 
                                   value="{{ old('ibu.nama', $orangTua->nama_ibu ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   required>
                            @error('ibu.nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir Ibu -->
                        <div>
                            <label for="tanggal_lahir_ibu" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir Ibu</label>
                            <input type="date" id="tanggal_lahir_ibu" name="ibu[tanggal_lahir]" 
                                   value="{{ old('ibu.tanggal_lahir', $orangTua && $orangTua->tanggal_lahir_ibu ? \Carbon\Carbon::parse($orangTua->tanggal_lahir_ibu)->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- Pekerjaan Ibu -->
                        <div>
                            <label for="pekerjaan_ibu" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ibu</label>
                            <input type="text" id="pekerjaan_ibu" name="ibu[pekerjaan]" 
                                   value="{{ old('ibu.pekerjaan', $orangTua->pekerjaan_ibu ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- Penghasilan Ibu -->
                        <div>
                            <label for="penghasilan_ibu" class="block text-sm font-medium text-gray-700 mb-2">Penghasilan Ibu (per bulan)</label>
                            <input type="number" id="penghasilan_ibu" name="ibu[penghasilan]" step="0.01" min="0"
                                   value="{{ old('ibu.penghasilan', $orangTua->penghasilan_ibu ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- NIK Ibu -->
                        <div>
                            <label for="nik_ibu" class="block text-sm font-medium text-gray-700 mb-2">NIK Ibu</label>
                            <input type="text" id="nik_ibu" name="ibu[nik]" 
                                   value="{{ old('ibu.nik', $orangTua->nik_ibu ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>

                        <!-- No HP Ibu -->
                        <div class="md:col-span-2">
                            <label for="no_hp_ibu" class="block text-sm font-medium text-gray-700 mb-2">No. HP Ibu *</label>
                            <input type="text" id="no_hp_ibu" name="ibu[no_hp]" 
                                   value="{{ old('ibu.no_hp', $orangTua->no_hp_ibu ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   required>
                            @error('ibu.no_hp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Ibu -->
                        <div class="md:col-span-2">
                            <label for="alamat_ibu" class="block text-sm font-medium text-gray-700 mb-2">Alamat Ibu *</label>
                            <textarea id="alamat_ibu" name="ibu[alamat]" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                      required>{{ old('ibu.alamat', $orangTua->alamat_ibu ?? '') }}</textarea>
                            @error('ibu.alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('data-keluarga.reset-type') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                        Kembali
                    </a>
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium flex items-center">
                        <i class="fas fa-save mr-2"></i>Simpan Data Orang Tua
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Data Wali -->
        @if($selectedType == 'wali')
        <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Header dengan tombol kembali -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <!-- <a href="{{ route('data-keluarga.reset-type') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Pilihan
                    </a> -->
                    <h2 class="text-xl font-bold text-gray-800">Data Wali</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ ($wali && $wali->nama_wali) ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        <i class="fas {{ ($wali && $wali->nama_wali) ? 'fa-check' : 'fa-clock' }} mr-1"></i>
                        {{ ($wali && $wali->nama_wali) ? 'Lengkap' : 'Belum Lengkap' }}
                    </span>
                    @if($wali)
                    <form action="{{ route('data-keluarga.delete') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data wali?')">
                        @csrf
                        <input type="hidden" name="formulir_id" value="{{ $formulir->id }}">
                        <input type="hidden" name="type" value="wali">
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i>Hapus Data
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <form action="{{ route('data-keluarga.store-wali') }}" method="POST">
                @csrf
                <input type="hidden" name="formulir_id" value="{{ $formulir->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Wali -->
                    <div>
                        <label for="nama_wali" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap Wali *</label>
                        <input type="text" id="nama_wali" name="nama_wali" 
                               value="{{ old('nama_wali', $wali->nama_wali ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               required>
                        @error('nama_wali')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No HP Wali -->
                    <div>
                        <label for="no_hp_wali" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon Wali *</label>
                        <input type="text" id="no_hp_wali" name="no_hp_wali" 
                               value="{{ old('no_hp_wali', $wali->no_hp_wali ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               required>
                        @error('no_hp_wali')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Alamat Wali -->
                <div class="mt-6">
                    <label for="alamat_wali" class="block text-sm font-medium text-gray-700 mb-2">Alamat Wali *</label>
                    <textarea id="alamat_wali" name="alamat_wali" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                              required>{{ old('alamat_wali', $wali->alamat_wali ?? '') }}</textarea>
                    @error('alamat_wali')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('data-keluarga.reset-type') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                        Kembali
                    </a>
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-medium">
                        <i class="fas fa-save mr-2"></i>Simpan Data Wali
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>

<script>
// Auto submit form ketika pilihan dipilih
document.addEventListener('DOMContentLoaded', function() {
    const radioInputs = document.querySelectorAll('input[name="type"]');
    radioInputs.forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('typeForm').submit();
        });
    });
});
</script>
@endsection