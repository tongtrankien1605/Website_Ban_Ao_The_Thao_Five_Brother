<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataSeeder = [
            [
                'name' => 'Chưa thanh toán'
            ],
            // [
            //     'name' => 'Chờ thanh toán'
            // ],
            [
                'name' => 'Đã thanh toán'
            ],
            [
                'name' => 'Đã hoàn tiền'
            ],
        ];
        DB::table('payment_method_statuses')->insert($dataSeeder);
    }
}
