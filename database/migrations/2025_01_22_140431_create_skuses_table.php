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
        Schema::create('skuses', function (Blueprint $table) {
            $table->id()->comment('ID sản phẩm biến thể');
            $table->unsignedBigInteger('product_id');
            $table->string('name');
            $table->integer('price');
            $table->integer('sale_price');
            $table->string('image')->nullable();
            $table->string('barcode')->unique()->comment('Mã vạch sản phẩm');
            $table->boolean('status')->default(1)->comment('Trạng thái sản phẩm');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skuses');
    }
};
