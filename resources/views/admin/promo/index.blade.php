@extends('layouts.admin')

@section('title', 'Data Promo')

@section('content')
<div class="card shadow-sm rounded-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-tags me-2"></i>Data Promo</h5>

        <a href="{{ route('admin.promo.create') }}" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Promo
        </a>
    </div>

    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-primary">
                <tr class="text-center">
                    <th>#</th>
                    <th>Keterangan</th>
                    <th>Nominal Potongan</th>
                    <th>kode promo</th>
                    <th>Status</th>
                    <th width="20%">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($promos as $promo)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $promo->jenis_promo }}</td>
                        <td>{{ number_format($promo->nominal_potongan) }}</td>
                        <td>{{ $promo->keterangan ?? '-' }}</td>
                        <td>
                            @if ($promo->is_aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.promo.edit', $promo->id) }}" 
                               class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <form action="{{ route('admin.promo.destroy', $promo->id) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus promo ini?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Belum ada data promo.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $promos->links() }}
        </div>
    </div>
</div>
@endsection
