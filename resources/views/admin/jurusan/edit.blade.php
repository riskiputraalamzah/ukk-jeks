@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Jurusan</h2>

    <form action="{{ route('admin.jurusan.update', $jurusan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama Jurusan</label>
            <input type="text" name="nama" value="{{ $jurusan->nama }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Kode Jurusan</label>
            <input type="text" name="kode_jurusan" value="{{ $jurusan->kode_jurusan }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
