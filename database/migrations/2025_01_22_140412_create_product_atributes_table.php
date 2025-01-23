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
        Schema::create('product_atributes', function (Blueprint $table) {
            $table->id()->comment('ID thuộc tính sản phẩm');
            $table->string('name')->comment('Tên thuộc tính, vd: kích thước, kiểu dáng, chất liệu');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_atributes');
    }
};
