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
            $table->id();
            $table->foreignId('id_brand')->constrained('brands');
            $table->text('description')->nullable()->comment('Mô tả sản phẩm');
            $table->foreignId('id_category')->constrained('categories');
            $table->string('image', 255)->nullable()->comment('Ảnh chính');
            $table->decimal('price', 10, 2)->comment('Giá biến thể sản phẩm');
            $table->timestamps();
            $table->softDeletes();
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
