@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Data Jurusan</h2>
    <a href="{{ route('admin.jurusan.create') }}" class="btn btn-primary mb-3">+ Tambah Jurusan</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Kode Jurusan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jurusans as $j)
                <tr>
                    <td>{{ $j->id }}</td>
                    <td>{{ $j->nama }}</td>
                    <td>{{ $j->kode_jurusan }}</td>
                    <td>
                        <a href="{{ route('admin.jurusan.edit', $j->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.jurusan.destroy', $j->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus jurusan ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
