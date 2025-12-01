<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\GelombangPendaftaran;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role Admin & User
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Admin']
        );

        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            ['display_name' => 'User']
        );

        // Buat user admin default
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'username' => 'admin',
                'password' => Hash::make('password'),
                'no_hp' => '08123456789'
            ]
        );


        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'no_hp' => '08123456789'
            ]
        );

        // Hubungkan role admin ke user admin
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // (Opsional) Tambahkan user biasa untuk tes
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'username' => 'user',
                'password' => Hash::make('password'),
                'no_hp' => '08123456780'
            ]
        );
        $user->roles()->syncWithoutDetaching([$userRole->id]);


        // Buat beberapa gelombang pendaftaran
        GelombangPendaftaran::create(
            [
                'nama_gelombang' => 'Gelombang 1',
                'tanggal_mulai' => '2024-07-01',
                'tanggal_selesai' => '2024-08-31',
                'harga' => 1000000,
                'limit_siswa' => 100,
            ]
        );
        GelombangPendaftaran::create(
            [
                'nama_gelombang' => 'Gelombang 2',
                'tanggal_mulai' => '2024-09-01',
                'tanggal_selesai' => '2024-10-31',
                'harga' => 2000000,
                'limit_siswa' => 100,
            ]
        );

        // bikin jurusan
        Jurusan::create([
            'id' => 1,
            'kode_jurusan' => 'RPL',
            'nama' => 'Rekayasa Perangkat Lunak',
        ]);
        Jurusan::create([
            'id' => 2,
            'kode_jurusan' => 'TKR',
            'nama' => 'Teknik Kendaraan Ringan',
        ]);
        Jurusan::create([
            'id' => 3,
            'kode_jurusan' => 'TPM',
            'nama' => 'Teknik Pemesinan',
        ]);
        Jurusan::create([
            'id' => 4,
            'kode_jurusan' => 'TITL',
            'nama' => 'Teknik Instalasi Tenaga Listrik',
        ]);
        Jurusan::create([
            'id' => 5,
            'kode_jurusan' => 'TEI',
            'nama' => 'Teknik Elektronika Industri',
        ]);

        //kelas

        Kelas::create([
            'nama_kelas' => 'X RPL 1',
            'jurusan_id' => 1,
            'tipe_kelas' => 'Unggulan',
            'kapasitas' => 32,
            'tahun_ajaran' => '2025',
        ]);
        Kelas::create([
            'nama_kelas' => 'X TKR 1',
            'jurusan_id' => 2,
            'tipe_kelas' => 'Reguler',
            'kapasitas' => 32,
            'tahun_ajaran' => '2025',
        ]);
        Kelas::create([
            'nama_kelas' => 'X TPM 1',
            'jurusan_id' => 3,
            'tipe_kelas' => 'Reguler',
            'kapasitas' => 32,
            'tahun_ajaran' => '2025',
        ]);
        Kelas::create([
            'nama_kelas' => 'X TITL 1',
            'jurusan_id' => 4,
            'tipe_kelas' => 'Reguler',
            'kapasitas' => 32,
            'tahun_ajaran' => '2025',
        ]);
        Kelas::create([
            'nama_kelas' => 'X TEI 1',
            'jurusan_id' => 5,
            'tipe_kelas' => 'Reguler',
            'kapasitas' => 32,
            'tahun_ajaran' => '2025',
        ]);
    }
}
