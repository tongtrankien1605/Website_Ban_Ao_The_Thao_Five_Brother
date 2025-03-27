<?php

use App\Models\Skus;
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
        Schema::create('inventory_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_skus');
            $table->foreign('id_skus')->references('id')->on('skuses')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->comment('id của người thêm');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->integer('quantity');
            $table->integer('cost_price')->comment('Giá nhập sản phẩm');
            $table->integer('price')->comment('Giá bán sản phẩm');
            $table->integer('sale_price')->comment('Giá khuyến mãi')->nullable();
            $table->dateTime('discount_start')->comment('Thời gian bắt đầu khuyến mãi')->nullable();
            $table->dateTime('discount_end')->comment('Thời gian kết thúc khuyến mãi')->nullable();
            $table->string('note')->comment('comment khi nhập hàng')->nullable();
            $table->enum('status', ['Đang chờ xử lý', 'Đã duyệt'])->comment('Trạng thái xử lý xuất nhập kho');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_entries');
    }
};
