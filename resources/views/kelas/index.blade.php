@extends('layouts.app')
@section('content')
    <div class="mb-3 d-flex justify-content-between">
        <h3>Kelas</h3>
        <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Tipe</th>
                <th>Kapasitas</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kelas as $k)
                <tr>
                    <td>{{ $k->id }}</td>
                    <td>{{ $k->nama_kelas }}</td>
                    <td>{{ $k->jurusan->nama ?? '-' }}</td>
                    <td>{{ $k->tipe_kelas }}</td>
                    <td>{{ $k->kapasitas }}</td>
                    <td>{{ $k->tahun_ajaran }}</td>
                    <td>
                        <a href="{{ route('admin.kelas.show', $k) }}" class="btn btn-sm btn-info">Lihat</a>
                        <a href="{{ route('admin.kelas.edit', $k) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="d-inline">@csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $kelas->links() }}
@endsection
