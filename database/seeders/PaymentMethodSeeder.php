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
                'name' => 'Tiền mặt',
            ],
            [
                'name' => 'Paypal',
            ],
            [
                'name' => 'Vnpay',
            ],
        ];
        DB::table('payment_methods')->insert($dataSeeder);
    }
}
