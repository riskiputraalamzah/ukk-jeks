<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->string('midtrans_transaction_id')->nullable()->unique();
            $table->string('midtrans_status')->nullable();
            $table->string('midtrans_payment_type')->nullable();
            $table->json('midtrans_response')->nullable();
            
            // Update status enum untuk include Midtrans status
            $table->enum('status', ['Menunggu', 'Lunas', 'Pending', 'Failed', 'Expired'])->default('Menunggu')->change();
        });
    }

    public function down()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_order_id',
                'midtrans_transaction_id', 
                'midtrans_status',
                'midtrans_payment_type',
                'midtrans_response'
            ]);
            $table->enum('status', ['Menunggu', 'Lunas'])->default('Menunggu')->change();
        });
    }
};