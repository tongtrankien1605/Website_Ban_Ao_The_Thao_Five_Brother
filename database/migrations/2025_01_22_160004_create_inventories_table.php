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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id()->comment('Kho hàng');
            $table->unsignedBigInteger('id_product_variant');
            $table->integer('quantity')->comment('Số lượng còn trong kho hiện tại');
            $table->timestamps();
            $table->foreign('id_product_variant')->references('id')->on('skus')->cascadeOnDelete(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
