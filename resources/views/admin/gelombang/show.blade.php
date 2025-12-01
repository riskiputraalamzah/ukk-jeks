@extends('layouts.admin')
@section('content')
<h3>Detail Gelombang</h3>
<table class="table">
  <tr><th>Nama</th><td>{{ $gelombang->nama_gelombang }}</td></tr>
  <tr><th>Mulai</th><td>{{ $gelombang->tanggal_mulai }}</td></tr>
  <tr><th>Selesai</th><td>{{ $gelombang->tanggal_selesai }}</td></tr>
  <tr><th>Limit</th><td>{{ $gelombang->limit_siswa }}</td></tr>
  <tr><th>Catatan</th><td>{{ $gelombang->catatan }}</td></tr>
  <tr><th>Harga</th><td>Rp {{ number_format($gelombang->harga}}</td></tr>
</table>
<a href="{{ route('admin.gelombang.index') }}" class="btn btn-secondary">Kembali</a>
@endsection
