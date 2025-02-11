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
        Schema::create('products', function (Blueprint $table) {
            $table->id('id')->comment('ID sản phẩm');
            $table->unsignedBigInteger('id_brand')->nullable()->comment('ID thương hiệu');
            $table->text('description')->nullable()->comment('Mô tả sản phẩm');
            $table->unsignedBigInteger('id_category')->nullable()->comment('ID danh mục');
            $table->string('image')->nullable()->comment('Ảnh chính');
            $table->decimal('price', 10, 2)->comment('Giá sản phẩm');
            $table->timestamps();
            $table->foreign('id_brand')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('id_category')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
