<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('phone_number');
            $table->unsignedBigInteger('id_address');
            $table->unsignedBigInteger('id_order_status');
            $table->unsignedBigInteger('id_shipping_method');
            $table->unsignedBigInteger('id_payment_method');
            $table->decimal('total_amount', 10, 2)->comment('Tổng số lượng sản phẩm');
            $table->unsignedBigInteger('id_voucher')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('id_address')->references('id')->on('address_users')->cascadeOnDelete();
            $table->foreign('id_order_status')->references('id')->on('order_statuses')->cascadeOnDelete();
            $table->foreign('id_shipping_method')->references('id_shipping_method')->on('shipping_methods')->cascadeOnDelete();
            $table->foreign('id_payment_method')->references('id_payment_method')->on('payment_methods')->cascadeOnDelete();
            $table->foreign('id_voucher')->references('id')->on('vouchers')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
