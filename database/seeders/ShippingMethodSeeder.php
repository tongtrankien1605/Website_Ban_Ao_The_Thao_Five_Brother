<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataSeeder = [
            [
                'name' => 'Hỏa tốc',
                'cost' => 40000,
                'estimated_time' => ' 1 ngày',
                'status' => '1',
            ],
            [
                'name' => 'Nhanh',
                'cost' => 20000,
                'estimated_time' => '1-3 ngày',
                'status' => '1',
            ],
            [
                'name' => 'Tiết kiệm',
                'cost' => 10000,
                'estimated_time' => '3-5 ngày',
                'status' => '1',
            ],
        ];
        DB::table('shipping_methods')->insert($dataSeeder);
    }
}
