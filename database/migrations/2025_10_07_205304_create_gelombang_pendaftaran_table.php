<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGelombangPendaftaranTable extends Migration
{
    public function up()
    {
        Schema::create('gelombang_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gelombang',50);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('limit_siswa');
            $table->integer('harga');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gelombang_pendaftaran');
    }
}
