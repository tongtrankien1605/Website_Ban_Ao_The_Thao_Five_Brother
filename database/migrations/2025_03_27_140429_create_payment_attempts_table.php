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
        Schema::create('payment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->dateTime('started_at');
            $table->dateTime('expires_at');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }
    // m đéo ca composer à chạy 1 terminal cay vc ra

    // là sao ca composer â
    //cài á
    // cài rồi mà cc
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_attempts');
    }
};
