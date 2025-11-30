<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('formulir_pendaftaran', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['menunggu', 'diverifikasi', 'ditolak'])->default('menunggu')->after('gelombang_id');
            $table->text('catatan_verifikasi')->nullable()->after('status_verifikasi');
            $table->foreignId('admin_verifikasi_id')->nullable()->constrained('users')->onDelete('set null')->after('catatan_verifikasi');
            $table->timestamp('verified_at')->nullable()->after('admin_verifikasi_id');
        });
    }

    public function down()
    {
        Schema::table('formulir_pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['status_verifikasi', 'catatan_verifikasi', 'admin_verifikasi_id', 'verified_at']);
        });
    }
};