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
        Schema::create('refund_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('refund_id');
            $table->unsignedBigInteger('user_id');
            $table->string('note');
            $table->string('image')->comment('minh chứng');
            $table->timestamps();
            $table->foreign('refund_id')->references('id')->on('refunds');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_histories');
    }
};
