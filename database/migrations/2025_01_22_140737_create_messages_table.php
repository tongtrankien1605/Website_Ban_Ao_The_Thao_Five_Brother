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
        Schema::create('messages', function (Blueprint $table) {
            $table->id()->comment('Mã tin nhắn');
            $table->unsignedBigInteger('id_chat');
            $table->unsignedBigInteger('id_user');
            $table->text('message')->comment('Nội dung tin nhắn');
            $table->timestamp('created_at');
            $table->foreign('id_chat')->references('id')->on('chats')->cascadeOnDelete();
            $table->foreign('id_user')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
