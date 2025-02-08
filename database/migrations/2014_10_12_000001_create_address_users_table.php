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
        Schema::create('address_users', function (Blueprint $table) {
            $table->id('id')->comment('ID địa chỉ');
            $table->unsignedBigInteger('id_user')->comment('ID người dùng');
            $table->string('address')->nullable(false)->comment('Địa chỉ');
            $table->boolean('is_default')->default(false)->comment('Địa chỉ mặc định');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address_users');
    }
};
