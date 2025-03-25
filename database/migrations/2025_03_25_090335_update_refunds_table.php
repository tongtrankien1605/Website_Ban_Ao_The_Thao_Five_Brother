<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRefundsTable extends Migration
{
    public function up()
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->integer('refund_quantity')->after('refund_amount');
            $table->enum('status', ['Đang chờ xử lý', 'Đã chấp nhận', 'Đã từ chối'])->default('Đang chờ xử lý')->after('refund_quantity');
        });
    }

    public function down()
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropColumn(['refund_amount', 'refund_quantity', 'status']);
        });
    }
}
