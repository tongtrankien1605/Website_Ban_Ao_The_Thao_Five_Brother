<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('note_user');
            $table->string('note_admin')->nullable();
            $table->string('image')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('status')->default('Chờ xử lý'); // 'Chờ xử lý', 'Đã xử lý',
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_histories');
    }
}; 