<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAtributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataSeeder = [
            [
                'product_attribute_id'=> 3,
                'value'=>'S'
            ],
            [
                'product_attribute_id'=> 3,
                'value'=>'M'
            ],
            [
                'product_attribute_id'=> 3,
                'value'=>'L'
            ],
            [
                'product_attribute_id'=> 2,
                'value'=>'Vàng'
            ],
            [
                'product_attribute_id'=> 2,
                'value'=>'Đỏ'
            ],
            [
                'product_attribute_id'=> 2,
                'value'=>'Xanh'
            ],
            [
                'product_attribute_id'=> 1,
                'value'=>'Cotton'
            ],
            [
                'product_attribute_id'=> 1,
                'value'=>'vải dù'
            ],
        ];
        DB::table('product_atribute_values')->insert($dataSeeder);
    }
}
