<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataSeeder = [
            [
                'id_payment_method_status'=>1,
                'name' => 'Tiền mặt',
                // 'status' => '1',
            ],
            [
                'id_payment_method_status'=>2,
                'name' => 'Tiền mặt',
                // 'status' => '1',
            ],
            [
                'id_payment_method_status'=>3,
                'name' => 'Tiền mặt',
                // 'status' => '1',
            ],
            [
                'id_payment_method_status'=>1,
                'name' => 'Chuyển khoản',
                // 'status' => '1',
            ],
            [
                'id_payment_method_status'=>2,
                'name' => 'Chuyển khoản',
                // 'status' => '1',
            ],
            [
                'id_payment_method_status'=>3,
                'name' => 'Chuyển khoản',
                // 'status' => '1',
            ],
        ];
        DB::table('payment_methods')->insert($dataSeeder);
    }
}
