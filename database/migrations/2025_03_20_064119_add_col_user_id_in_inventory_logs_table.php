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
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->comment('id người đổi số lượng')->nullable()->after('id_product_variant');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->integer('old_quantity')->after('user_id');
            $table->integer('new_quantity')->after('old_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            //
        });
    }
};
