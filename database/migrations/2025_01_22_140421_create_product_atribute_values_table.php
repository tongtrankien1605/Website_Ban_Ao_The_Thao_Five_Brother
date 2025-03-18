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
        Schema::create('product_atribute_values', function (Blueprint $table) {
            $table->id()->comment('ID giá trị thuộc tính sản phẩm');
            $table->unsignedBigInteger('product_attribute_id');
            $table->string('value')->comment('Giá trị của thuộc tính (ví dụ: S, M, L, đỏ, xanh, vàng, cotton...)');
            $table->foreign('product_attribute_id')->references('id')->on('product_atributes')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_atribute_values');
    }
};
