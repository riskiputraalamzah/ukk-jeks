<!DOCTYPE html>
<html>

<head>
    <title>Bukti Pendaftaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }

        .content {
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
            width: 30%;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: #666;
        }

        .info-box {
            background-color: #eef2ff;
            border: 1px solid #c7d2fe;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .info-box h3 {
            margin-top: 0;
            color: #312e81;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>SMK NEGERI 1 CONTOH</h1>
        <p>Jl. Pendidikan No. 123, Kota Contoh, Telp: (021) 1234567</p>
        <p>Website: www.smkn1contoh.sch.id | Email: info@smkn1contoh.sch.id</p>
    </div>

    <div class="content">
        <h2 style="text-align: center; text-decoration: underline;">BUKTI PENDAFTARAN SISWA BARU</h2>
        <p style="text-align: center;">Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}</p>

        <h3>A. Data Pendaftaran</h3>
        <table class="table">
            <tr>
                <th>Nomor Pendaftaran</th>
                <td>{{ $formulir->nomor_pendaftaran }}</td>
            </tr>
            <tr>
                <th>Tanggal Daftar</th>
                <td>{{ $formulir->created_at->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Jurusan</th>
                <td>{{ $formulir->jurusan->nama ?? '-' }}</td>
            </tr>
            <tr>
                <th>Gelombang</th>
                <td>{{ $formulir->gelombang->nama_gelombang ?? '-' }}</td>
            </tr>
            <tr>
                <th>Kelas</th>
                <td>{{ $formulir->kelas->nama_kelas ?? 'Belum ditentukan' }}</td>
            </tr>
        </table>

        <h3>B. Data Calon Siswa</h3>
        <table class="table">
            <tr>
                <th>NISN</th>
                <td>{{ $formulir->nisn }}</td>
            </tr>
            <tr>
                <th>Nama Lengkap</th>
                <td>{{ $formulir->nama_lengkap }}</td>
            </tr>
            <tr>
                <th>Tempat, Tanggal Lahir</th>
                <td>{{ $formulir->tempat_lahir }}, {{ $formulir->tanggal_lahir->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>{{ $formulir->jenis_kelamin }}</td>
            </tr>
            <tr>
                <th>Asal Sekolah</th>
                <td>{{ $formulir->asal_sekolah }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $formulir->alamat }}</td>
            </tr>
        </table>

        <div class="info-box">
            <h3>Informasi Penting</h3>
            <p>Silakan bawa bukti pendaftaran ini ke sekolah untuk:</p>
            <ul>
                <li>Pengambilan seragam sekolah.</li>
                <li>Verifikasi berkas fisik (jika diperlukan).</li>
                <li>Informasi pembagian jadwal pelajaran.</li>
            </ul>
            <p><strong>Catatan:</strong> Harap simpan dokumen ini dengan baik sebagai bukti sah pendaftaran Anda.</p>
        </div>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>

</html>