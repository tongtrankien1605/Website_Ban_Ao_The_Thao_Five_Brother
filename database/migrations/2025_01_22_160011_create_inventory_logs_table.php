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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id()->comment('Nhật ký kiểm kê: nhập kho; hoàn hàng');
            $table->unsignedBigInteger('id_product_variant');
            $table->integer('change_quantity')->comment('Số lượng thay đổi');
            $table->string('reason')->comment('Lý do thay đổi (nhập kho, trả hàng...)');
            $table->timestamps();
            $table->foreign('id_product_variant')->references('id')->on('skus')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
