<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoTable extends Migration
{
    public function up()
    {
        Schema::create('promo', function (Blueprint $table) {
            $table->id();
            $table->string('kode_promo', 100);
            $table->integer('nominal_potongan');
            $table->text('keterangan')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promo');
    }
}
