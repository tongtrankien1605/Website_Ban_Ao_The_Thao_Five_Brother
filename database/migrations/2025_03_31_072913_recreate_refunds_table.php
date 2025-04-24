<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('refunds');

        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_order');
            $table->text('reason');
            $table->decimal('refund_amount', 15, 2)->nullable();
            $table->integer('refund_quantity')->nullable();
            $table->enum('status', ['Đang chờ xử lý', 'Đã chấp nhận', 'Đã từ chối'])->default('Đang chờ xử lý');
            $table->string('image_path')->nullable();
            $table->string('video_path')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }
};
