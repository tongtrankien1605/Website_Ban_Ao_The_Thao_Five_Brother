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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_order');
            $table->unsignedBigInteger('id_product_variant');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2)->comment('Giá từng sản phẩm');
            $table->decimal('total_price', 15, 2)->comment('Tổng tiền đơn hàng');
            $table->foreign('id_order')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('id_product_variant')->references('id')->on('skuses')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
