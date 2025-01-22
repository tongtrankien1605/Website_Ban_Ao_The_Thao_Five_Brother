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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('discount_type', ['percentage', 'fixed'])->comment('Loại giảm giá');
            $table->decimal('discount_value', 10, 2)->comment('Giá trị giảm giá');
            $table->integer('total_usage')->comment('Tổng số lượt sử dụng được phép');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->boolean('status')->comment('0: còn, 1: hết');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
