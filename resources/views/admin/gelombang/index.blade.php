@extends('layouts.admin')

@section('title','Data Gelombang')

@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-calendar-event"></i> Data Gelombang
    </div>

    <div class="card-body">
        <a href="{{ route('admin.gelombang.create') }}" class="btn btn-primary btn-sm mb-3">
            + Tambah Gelombang
        </a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Gelombang</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Limit</th>
                     <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($gelombangs as $g)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $g->nama_gelombang }}</td>
                    <td>{{ $g->tanggal_mulai }}</td>
                    <td>{{ $g->tanggal_selesai }}</td>
                    <td>{{ $g->limit_siswa }}</td>
                    <td>{{ $g->harga}}</td>
                 
                    <td>
                        <a href="{{ route('admin.gelombang.edit',$g) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('admin.gelombang.destroy',$g) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Belum ada data gelombang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $gelombangs->links() }}
    </div>
</div>

@endsection
