@extends('layouts.admin')

@section('title', 'Edit Promo')

@section('content')
<div class="card shadow-sm rounded-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Promo</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.promo.update', $promo->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Jenis Promo</label>
                <input type="text" name="jenis_promo" class="form-control" 
                       value="{{ $promo->jenis_promo }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nominal Potongan</label>
                <input type="number" name="nominal_potongan" class="form-control"
                       value="{{ $promo->nominal_potongan }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control">{{ $promo->keterangan }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status Promo</label>
                <select name="is_aktif" class="form-control">
                    <option value="1" {{ $promo->is_aktif ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$promo->is_aktif ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <button class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
            <a href="{{ route('admin.promo.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
