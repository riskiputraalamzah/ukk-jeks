@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Dashboard Admin</h1>
<p>Selamat datang, <strong>{{ auth()->user()->username ?? auth()->user()->email }}</strong>!</p>

<div class="grid grid-cols-4 gap-4 mt-6">
    <div class="p-4 bg-white shadow rounded-lg">
        <h2 class="text-gray-500 text-sm">Total Users</h2>
        <p class="text-2xl font-bold">{{ $data['total_users'] }}</p>
    </div>
    <div class="p-4 bg-white shadow rounded-lg">
        <h2 class="text-gray-500 text-sm">Total Jurusan</h2>
        <p class="text-2xl font-bold">{{ $data['total_jurusan'] }}</p>
    </div>
    <div class="p-4 bg-white shadow rounded-lg">
        <h2 class="text-gray-500 text-sm">Total Gelombang</h2>
        <p class="text-2xl font-bold">{{ $data['total_gelombang'] }}</p>
    </div>
    <div class="p-4 bg-white shadow rounded-lg">
        <h2 class="text-gray-500 text-sm">Total Pembayaran</h2>
        <p class="text-2xl font-bold">{{ $data['total_pembayaran'] }}</p>
    </div>
</div>
@endsection
