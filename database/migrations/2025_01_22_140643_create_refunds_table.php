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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id()->comment('Hoàn hàng');
            $table->unsignedBigInteger('id_order');
            $table->text('reason')->comment('Lý do');
            $table->decimal('refund_amount', 10, 2)->comment('Tổng số lượng hàng hoàn trả');
            $table->enum('status', ['accepted', 'rejected'])->comment('Chấp nhận, không chấp nhận');
            $table->timestamps();
            $table->foreign('id_order')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
