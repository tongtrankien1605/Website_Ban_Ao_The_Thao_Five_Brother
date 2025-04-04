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
        Schema::table('inventory_entries', function (Blueprint $table) {
            $table->unsignedBigInteger('id_shopper')->comment('id của người duyệt hàng')->nullable()->after('user_id');
            $table->foreign('id_shopper')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventoriy_entries', function (Blueprint $table) {
            //
        });
    }
};
