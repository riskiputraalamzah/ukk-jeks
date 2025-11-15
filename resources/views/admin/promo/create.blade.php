@extends('layouts.admin')

@section('title', 'Tambah Promo')

@section('content')
<div class="card shadow-sm rounded-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Promo</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.promo.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Jenis Promo</label>
                <input type="text" name="jenis_promo" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nominal Potongan</label>
                <input type="number" name="nominal_potongan" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status Promo</label>
                <select name="is_aktif" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('admin.promo.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
