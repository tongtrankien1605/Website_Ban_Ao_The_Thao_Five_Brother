<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAtributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataSeeder = [
            [
                'name'=>'Chất liệu'
            ],
            [
                'name'=>'Màu sắc'
            ],
            [
                'name'=>'Kích cỡ'
            ],
        ];
        DB::table('product_atributes')->insert($dataSeeder);
    }
}
