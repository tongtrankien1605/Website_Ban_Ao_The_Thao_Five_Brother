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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id('id_payment_method')->comment('ID phương thức thanh toán');
            // $table->unsignedBigInteger('id_payment_method_status')->comment('ID phương thức thanh toán');
            // $table->foreign('id_payment_method_status')->references('id')->on('payment_method_statuses');
            $table->string('name')->comment('Tên phương thức thanh toán');
            //$table->string('status')->comment('Trạng thái'); tách trạng thái và phương thức ra làm 2 bảng riêng lẻ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
