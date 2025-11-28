<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrangTuaTable extends Migration
{
    public function up()
    {
        Schema::create('orang_tua', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulir_id');
            // Ayah
            $table->string('nama_ayah', 100)->nullable();
            $table->date('tanggal_lahir_ayah')->nullable();
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->decimal('penghasilan_ayah', 15, 2)->nullable();
            $table->text('alamat_ayah')->nullable();
            $table->string('no_hp_ayah', 20)->nullable();
            $table->string('nik_ayah', 20)->unique()->nullable();
            // Ibu
            $table->string('nama_ibu', 100)->nullable();
            $table->date('tanggal_lahir_ibu')->nullable();
            $table->string('pekerjaan_ibu', 100)->nullable();
            $table->decimal('penghasilan_ibu', 15, 2)->nullable();
            $table->text('alamat_ibu')->nullable();
            $table->string('no_hp_ibu', 20)->nullable();
            $table->string('nik_ibu', 20)->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orang_tua');
    }
}
