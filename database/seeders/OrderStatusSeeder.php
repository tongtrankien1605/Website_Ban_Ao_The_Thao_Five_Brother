<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataSeeder = [
            [
                'name' => 'Chờ xác nhận'
            ],
            [
                'name' => 'Đã xác nhận'
            ],
            [
                'name' => 'Đang giao hàng'
            ],
            [
                'name' => 'Chờ lấy hàng'
            ],
            [
                'name' => 'Đã giao'
            ],
            [
                'name' => 'Giao thất bại'
            ],
            [
                'name' => 'Hoàn trả'
            ],
            [
                'name' => 'Đã hủy'
            ],
        ];
        DB::table('order_statuses')->insert($dataSeeder);
    }
}
