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
        Schema::create('categories', function (Blueprint $table) {
            $table->id('id')->comment('ID danh mục');
            $table->string('name', 100)->unique()->nullable(false)->comment('Tên danh mục (duy nhất)');
            $table->string('description', 255)->nullable()->comment('Mô tả');
            $table->string('image', 255)->nullable()->comment('Hình ảnh danh mục');
            $table->boolean('is_active')->default(1)->comment('1 là danh mục đang hiển thị, 0 nếu ẩn');
            $table->timestamps(); // Tự động tạo created_at và updated_at
            $table->softDeletes()->comment('Thời gian xóa mềm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
