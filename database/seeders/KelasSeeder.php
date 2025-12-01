<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Jurusan;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Pastikan jurusan ada
        $jurusans = Jurusan::all();

        if ($jurusans->count() == 0) {
            $this->command->info('Jurusan belum ada, silakan jalankan JurusanSeeder terlebih dahulu.');
            return;
        }

        foreach ($jurusans as $jurusan) {
            // Buat 3 kelas untuk setiap jurusan
            for ($i = 1; $i <= 3; $i++) {
                Kelas::create([
                    'jurusan_id' => $jurusan->id,
                    'nama_kelas' => 'X ' . $jurusan->kode_jurusan . ' ' . $i,
                    'tipe_kelas' => 'Reguler',
                    'kapasitas' => 20,
                    'tahun_ajaran' => date('Y'),
                ]);
            }
        }
    }
}
