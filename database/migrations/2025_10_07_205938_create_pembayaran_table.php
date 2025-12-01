<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranTable extends Migration
{
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulir_id')->constrained('formulir_pendaftaran')->onDelete('cascade');
            $table->dateTime('tanggal_bayar')->nullable();
            $table->string('metode_bayar')->nullable();
            $table->decimal('jumlah_awal', 12, 2)->nullable();
            $table->foreignId('promo_voucher_id')->nullable()->constrained('promo')->onDelete('set null');
            $table->decimal('jumlah_akhir', 12, 2)->nullable();
            $table->enum('status', ['Menunggu', 'Lunas'])->default('Menunggu');
            $table->string('kode_transaksi', 50)->unique()->nullable();
            $table->string('no_kuitansi', 50)->unique()->nullable();
            $table->string('bukti_bayar', 255)->nullable();
            $table->foreignId('admin_verifikasi_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('verified_at')->nullable();
            $table->text('catatan')->nullable();
            $table->string('path_nota_pdf', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
}
