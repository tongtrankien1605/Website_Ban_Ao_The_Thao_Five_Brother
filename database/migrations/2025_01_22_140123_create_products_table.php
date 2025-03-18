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
            $table->string('name', 255)->comment('tên gốc của sản phẩm');
            $table->string('image')->nullable()->comment('Ảnh đại diện cho sản phẩm');
            $table->text('description')->nullable()->comment('Mô tả sản phẩm');
            $table->boolean('status')->default(1)->comment('Trạng thái sản phẩm');
            $table->foreignId('id_category')->constrained('categories');
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
