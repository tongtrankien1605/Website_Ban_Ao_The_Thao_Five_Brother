<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataSeeder = [
            [
                'id_user'=>1,
                'address'=>'Hà nội',
                'is_default'=>true,
            ],
            [
                'id_user'=>2,
                'address'=>'Hà nội',
                'is_default'=>true,
            ],
            [
                'id_user'=>3,
                'address'=>'Hà nội',
                'is_default'=>true,
            ],
            [
                'id_user'=>4,
                'address'=>'Hà nội',
                'is_default'=>true,
            ],
            [
                'id_user'=>5,
                'address'=>'Hà nội',
                'is_default'=>true,
            ],
        ];
        DB::table('address_users')->insert($dataSeeder);
    }
}
