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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 20)->unique()->comment('Số điện thoại (duy nhất)');
            $table->string('email', 100)->unique()->nullable()->comment('Email (duy nhất)');
            $table->string('password', 255)->comment('Mật khẩu đã mã hóa');
            $table->string('fullname', 100)->nullable()->comment('Họ và tên');
            $table->string('avatar', 255)->nullable()->comment('Ảnh đại diện');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->comment('Giới tính');
            $table->date('birthday')->nullable()->comment('Ngày sinh');
            $table->foreignId('role')->default(1)->constrained('roles')->comment('Vai trò người dùng');
            $table->boolean('status')->default(true)->comment('Trạng thái tài khoản');
            $table->string('remember_token', 100)->nullable()->comment('Ghi nhớ token');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
