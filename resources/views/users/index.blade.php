<x-app-layout>
    <div class="container py-4">
        <h1 class="mb-4">Manajemen Pengguna</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
               @foreach($users as $u)
<tr>
  <td>{{ $u->id }}</td>
  <td>{{ $u->email }}</td>
  <td>{{ implode(', ', $u->roles->pluck('name')->toArray()) }}</td>
  <td>
      <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-sm btn-warning">Edit</a>
  </td>
</tr>
@endforeach

            </tbody>
        </table>
    </div>
</x-app-layout>

@section('content')
<h3>Daftar User</h3>
<table class="table table-striped">
<thead><tr><th>#</th><th>Email</th><th>Roles</th><th>Aksi</th></tr></thead>
<tbody>
@foreach($users as $u)
<tr>
  <td>{{ $u->id }}</td>
  <td>{{ $u->email }}</td>
  <td>{{ implode(', ',$u->roles->pluck('name')->toArray()) }}</td>
  <td><a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-warning">Edit</a>
</td>
</tr>
@endforeach
</tbody>
</table>
@endsection
