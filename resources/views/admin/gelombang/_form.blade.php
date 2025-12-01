@csrf
<div class="mb-3">
  <label class="form-label">Nama Gelombang</label>
  <input type="text" name="nama_gelombang" class="form-control" value="{{ old('nama_gelombang', $gelombang->nama_gelombang ?? '') }}" required>
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <label class="form-label">Tanggal Mulai</label>
    <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $gelombang->tanggal_mulai ?? '') }}" required>
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Tanggal Selesai</label>
    <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $gelombang->tanggal_selesai ?? '') }}" required>
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Limit Siswa</label>
    <input type="number" name="limit_siswa" class="form-control" value="{{ old('limit_siswa', $gelombang->limit_siswa ?? 0) }}" required>
  </div>

   <div class="col-md-4 mb-3">
    <label class="form-label">Harga</label>
    <input type="number" name="harga" class="form-control" value="{{ old('harga', $gelombang->harga ?? 0) }}" required>
</div>

</div>

<div class="mb-3">
  <label class="form-label">Catatan</label>
  <textarea name="catatan" class="form-control">{{ old('catatan', $gelombang->catatan ?? '') }}</textarea>
</div>

<button class="btn btn-primary" type="submit">Simpan</button>
