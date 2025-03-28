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
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id('id_shipping_method')->comment('ID phương thức giao hàng');
            $table->string('name');
            $table->decimal('cost', 10, 2)->comment('Giá ship');
            $table->string('estimated_time')->comment('Thời gian dự kiến giao hàng');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
