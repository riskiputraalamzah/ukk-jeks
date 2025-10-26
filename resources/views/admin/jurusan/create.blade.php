@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Tambah Jurusan</h2>

    <form action="{{ route('admin.jurusan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Jurusan</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Kode Jurusan</label>
            <input type="text" name="kode_jurusan" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
