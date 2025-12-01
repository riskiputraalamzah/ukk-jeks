@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Upload Dokumen Pendaftaran</h1>
            <p class="text-gray-600 mt-2">Lengkapi semua dokumen yang diperlukan untuk proses pendaftaran</p>
        </div>

        <!-- Status Ringkasan -->
        @php
        $form = auth()->user()->formulir()->first();
        $dokumenDiupload = 0;
        $totalDokumen = 6;

        // Query manual untuk menghindari error relasi
        if ($form) {
        try {
        $dokumenDiupload = \App\Models\DokumenPendaftaran::where('formulir_id', $form->id)->count();
        } catch (\Exception $e) {
        $dokumenDiupload = 0;
        }
        }
        @endphp

        @if(!$form)
        <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                <div>
                    <h3 class="font-semibold text-red-800">Formulir Belum Diisi</h3>
                    <p class="text-red-700 text-sm mt-1">
                        Anda harus mengisi formulir pendaftaran terlebih dahulu sebelum mengupload dokumen.
                    </p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('formulir.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                    <i class="fas fa-file-alt mr-2"></i>
                    Isi Formulir Pendaftaran
                </a>
            </div>
        </div>
        @else
        <!-- Progress Dokumen -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Status Kelengkapan Dokumen</h2>
                <div class="text-right">
                    <span class="text-2xl font-bold {{ $dokumenDiupload == $totalDokumen ? 'text-green-600' : 'text-blue-600' }}">
                        {{ $dokumenDiupload }}/{{ $totalDokumen }}
                    </span>
                    <p class="text-sm text-gray-600">dokumen terupload</p>
                </div>
            </div>

            @if($dokumenDiupload == $totalDokumen)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-green-800">Semua Dokumen Telah Lengkap!</h4>
                        <p class="text-green-700 text-sm">Anda dapat melanjutkan ke tahap pembayaran.</p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-yellow-800">Dokumen Belum Lengkap</h4>
                        <p class="text-yellow-700 text-sm">
                            Masih ada {{ $totalDokumen - $dokumenDiupload }} dokumen yang harus diupload.
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Daftar Dokumen -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Dokumen yang Diperlukan</h2>

            <div class="space-y-6">
                @foreach([
                'kartu_keluarga' => 'Kartu Keluarga',
                'akta_kelahiran' => 'Akta Kelahiran',
                'foto_3x4' => 'Foto 3x4',
                'surat_keterangan_lulus' => 'Surat Keterangan Lulus',
                'ijazah_sd' => 'Ijazah SD',
                'ktp_orang_tua' => 'KTP Ayah dan Ibu atau Wali'
                ] as $jenis => $label)

                @php
                // Query manual untuk setiap dokumen
                $dokumen = null;
                if ($form) {
                try {
                $dokumen = \App\Models\DokumenPendaftaran::where('formulir_id', $form->id)
                ->where('jenis_dokumen', $jenis)
                ->first();
                } catch (\Exception $e) {
                $dokumen = null;
                }
                }
                @endphp

                <div class="border border-gray-200 rounded-lg p-6 hover:border-blue-300 transition duration-200" id="dokumen-{{ $jenis }}">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <!-- Info Dokumen -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-800 text-lg mb-2">{{ $label }}</h3>
                                    <div class="flex items-center text-sm text-gray-600 mb-2">
                                        <i class="fas fa-file mr-2"></i>
                                        <span>Format:
                                            @if(in_array($jenis, ['foto_3x4']))
                                            JPG, PNG
                                            @elseif(in_array($jenis, ['surat_keterangan_lulus', 'ijazah_sd', 'ktp_orang_tua']))
                                            PDF
                                            @else
                                            PDF, JPG
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-weight-hanging mr-2"></i>
                                        <span>Maksimal: 2MB</span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="text-right">
                                    @if($dokumen)
                                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        <i class="fas fa-check mr-1"></i>
                                        Sudah
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium" id="status-{{ $jenis }}">
                                        <i class="fas fa-times mr-1"></i>
                                        Belum
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <!-- File Info jika sudah diupload -->
                            @if($dokumen)
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg" id="file-info-{{ $jenis }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @php
                                        // Helper function untuk cek apakah file gambar
                                        $isImage = in_array(strtolower($dokumen->extension), ['jpg', 'jpeg', 'png', 'gif']);
                                        // Helper function untuk format size
                                        $fileSize = $dokumen->size < 1024 ?
                                            number_format($dokumen->size, 0) . ' KB' :
                                            number_format($dokumen->size / 1024, 1) . ' MB';
                                            @endphp

                                            @if($isImage)
                                            <img src="{{ Storage::url($dokumen->path_file) }}"
                                                alt="Preview"
                                                class="w-12 h-12 object-cover rounded mr-3 cursor-pointer"
                                                onclick="showImagePreview('{{ Storage::url($dokumen->path_file) }}')">
                                            @else
                                            <div class="w-12 h-12 bg-blue-500 rounded flex items-center justify-center mr-3">
                                                <i class="fas fa-file-pdf text-white"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-800 text-sm">{{ $dokumen->original_name }}</p>
                                                <p class="text-gray-600 text-xs">{{ $fileSize }}</p>
                                            </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('dokumen.download', $dokumen->id) }}"
                                            class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 text-sm">
                                            <i class="fas fa-download mr-1"></i>
                                            Download
                                        </a>
                                        <form action="{{ route('dokumen.destroy', $dokumen->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200 text-sm"
                                                onclick="return confirm('Hapus dokumen ini?')">
                                                <i class="fas fa-trash mr-1"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Upload Form -->
                        @if(!$dokumen)
                        <div class="lg:w-64" id="upload-form-{{ $jenis }}">
                            <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data" class="upload-form" id="form-{{ $jenis }}" onsubmit="handleUpload(event, '{{ $jenis }}')">
                                @csrf
                                <input type="hidden" name="jenis_dokumen" value="{{ $jenis }}">
                                <input type="hidden" name="formulir_id" value="{{ $form->id }}">

                                <div class="flex flex-col space-y-2">
                                    <input type="file"
                                        name="file"
                                        class="file-input hidden"
                                        data-jenis="{{ $jenis }}"
                                        accept="{{ in_array($jenis, ['foto_3x4']) ? '.jpg,.jpeg,.png' : (in_array($jenis, ['surat_keterangan_lulus', 'ijazah_sd', 'ktp_orang_tua']) ? '.pdf' : '.pdf,.jpg,.jpeg,.png') }}"
                                        required>

                                    <button type="button"
                                        class="upload-trigger inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium w-full">
                                        <i class="fas fa-upload mr-2"></i>
                                        Upload File
                                    </button>

                                    <button type="submit"
                                        class="submit-btn  inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-medium w-full">
                                        <i class="fas fa-check mr-2"></i>
                                        Simpan
                                    </button>
                                </div>

                                <div class="file-info mt-2 text-sm text-gray-600 hidden"></div>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imagePreviewModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="max-w-4xl max-h-full">
        <img id="previewImage" src="" alt="Preview" class="max-w-full max-h-full">
        <button onclick="closeImagePreview()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<!-- Success Message Toast -->
<div id="successToast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 hidden transition-all duration-300">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <span id="successMessage">Dokumen berhasil diupload!</span>
    </div>
</div>

<style>
    .file-input:valid~.submit-btn {
        display: flex !important;
    }

    .file-input:valid~.upload-trigger {
        display: none !important;
    }
</style>

<script>
    // File upload handling
    document.addEventListener('DOMContentLoaded', function() {
        const uploadTriggers = document.querySelectorAll('.upload-trigger');
        const fileInputs = document.querySelectorAll('.file-input');
        const forms = document.querySelectorAll('.upload-form');

        // Setup file input triggers
        uploadTriggers.forEach((trigger, index) => {
            trigger.addEventListener('click', function() {
                fileInputs[index].click();
            });
        });

        // Setup file input change events
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                const form = this.closest('form');
                const fileInfo = form.querySelector('.file-info');
                const submitBtn = form.querySelector('.submit-btn');
                const uploadTrigger = form.querySelector('.upload-trigger');

                if (this.files.length > 0) {
                    const file = this.files[0];
                    const fileSizeMB = file.size / (1024 * 1024);
                    const maxSize = 2; // 2MB

                    // Validasi ukuran file
                    if (fileSizeMB > maxSize) {
                        alert('Ukuran file terlalu besar! Maksimal 2MB.');
                        this.value = '';
                        return;
                    }

                    // Validasi tipe file
                    const allowedTypes = this.getAttribute('accept').split(',').map(type => type.trim());
                    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

                    if (!allowedTypes.includes(fileExtension)) {
                        alert('Format file tidak diizinkan! Format yang diizinkan: ' + allowedTypes.join(', '));
                        this.value = '';
                        return;
                    }

                    fileInfo.innerHTML = `
                        <i class="fas fa-file mr-1"></i>
                        ${file.name} (${fileSizeMB.toFixed(2)} MB)
                    `;
                    fileInfo.classList.remove('hidden');

                    uploadTrigger.classList.add('hidden');
                    submitBtn.classList.remove('hidden');
                }
            });
        });

        // Setup form submission events
        forms.forEach(form => {
            form.addEventListener('submit', async function(event) {
                event.preventDefault();
                await handleUpload(event, this);
            });
        });
    });

    // Handle form submission dengan AJAX
    async function handleUpload(event, form) {
        const formData = new FormData(form);
        const submitBtn = form.querySelector('.submit-btn');
        const jenisDokumen = form.querySelector('input[name="jenis_dokumen"]').value;

        // Tampilkan loading state
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupload...';
        submitBtn.disabled = true;

        try {
            const response = await fetch(`{{ route('dokumen.store') }}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                // Tampilkan pesan sukses
                showSuccessMessage(result.message || 'Dokumen berhasil diupload!');

                // Update UI - sembunyikan form upload dan tampilkan status "Sudah"
                updateDokumenStatus(jenisDokumen, result.dokumen);

            } else {
                // Tampilkan error validasi
                if (result.errors) {
                    let errorMessage = '';
                    for (const field in result.errors) {
                        errorMessage += result.errors[field].join(', ') + '\n';
                    }
                    alert('Error: ' + errorMessage);
                } else {
                    alert('Error: ' + (result.message || 'Terjadi kesalahan saat upload'));
                }

                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }

        } catch (error) {
            alert('Error: ' + error.message);
            // Reset button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    // Function untuk update status dokumen setelah upload sukses
    function updateDokumenStatus(jenisDokumen, dokumenData) {
        const statusElement = document.getElementById(`status-${jenisDokumen}`);
        const uploadForm = document.getElementById(`upload-form-${jenisDokumen}`);
        const dokumenContainer = document.getElementById(`dokumen-${jenisDokumen}`);

        // Update status dari "Belum" menjadi "Sudah"
        if (statusElement) {
            statusElement.innerHTML = '<i class="fas fa-check mr-1"></i>Sudah';
            statusElement.className = 'inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium';
        }

        // Sembunyikan form upload
        if (uploadForm) {
            uploadForm.style.display = 'none';
        }

        // Tambahkan file info section
        const fileInfoHtml = `
            <div class="mt-4 p-3 bg-gray-50 rounded-lg" id="file-info-${jenisDokumen}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        ${dokumenData.is_image ? 
                            `<img src="${dokumenData.file_url}" alt="Preview" class="w-12 h-12 object-cover rounded mr-3 cursor-pointer" onclick="showImagePreview('${dokumenData.file_url}')">` :
                            `<div class="w-12 h-12 bg-blue-500 rounded flex items-center justify-center mr-3">
                                <i class="fas fa-file-pdf text-white"></i>
                            </div>`
                        }
                        <div>
                            <p class="font-medium text-gray-800 text-sm">${dokumenData.original_name}</p>
                            <p class="text-gray-600 text-xs">${dokumenData.file_size}</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="${dokumenData.download_url}" 
                           class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 text-sm">
                            <i class="fas fa-download mr-1"></i>
                            Download
                        </a>
                        <form action="${dokumenData.delete_url}" method="POST" class="inline">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200 text-sm"
                                    onclick="return confirm('Hapus dokumen ini?')">
                                <i class="fas fa-trash mr-1"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        `;

        // Tambahkan file info ke container
        const infoContainer = dokumenContainer.querySelector('.flex-1');
        const existingFileInfo = infoContainer.querySelector(`#file-info-${jenisDokumen}`);
        if (existingFileInfo) {
            existingFileInfo.remove();
        }
        infoContainer.insertAdjacentHTML('beforeend', fileInfoHtml);

        // Update progress counter dengan reload halaman setelah 2 detik
        // setTimeout(() => {
        window.location.reload();
        // }, 2000);
    }

    // Function untuk menampilkan pesan sukses
    function showSuccessMessage(message) {
        const toast = document.getElementById('successToast');
        const messageElement = document.getElementById('successMessage');

        messageElement.textContent = message;
        toast.classList.remove('hidden');

        // Sembunyikan setelah 3 detik
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }

    // Image preview functions
    function showImagePreview(imageUrl) {
        document.getElementById('previewImage').src = imageUrl;
        document.getElementById('imagePreviewModal').classList.remove('hidden');
    }

    function closeImagePreview() {
        document.getElementById('imagePreviewModal').classList.add('hidden');
    }

    // Close modal on background click
    document.getElementById('imagePreviewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImagePreview();
        }
    });
</script>
@endsection