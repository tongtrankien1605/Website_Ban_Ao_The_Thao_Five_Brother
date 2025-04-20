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
                'name' => 'Chờ lấy hàng'
            ],
            [
                'name' => 'Đang giao hàng'
            ],
            [
                'name' => 'Đã giao'
            ],
            [
                'name' => 'Giao thất bại'
            ],
            [
                'name' => 'Hoàn hàng'
            ],
            [
                'name' => 'Đã hủy'
            ],
            [
                'name' => 'Hoàn thành / Đã nhận được hàng'
            ],
            [
                'name' => 'Không chấp nhận hoàn hàng'
            ],
            [
                'name' => 'Chờ xác nhận hoàn hàng'
            ],
        ];
        DB::table('order_statuses')->insert($dataSeeder);
    }
}
