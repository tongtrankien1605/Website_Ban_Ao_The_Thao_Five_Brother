<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataSeeder = [
            [
                'name' => 'Nguyễn Thanh Phong',
                'email' => 'phongntph45917@fpt.edu.vn',
                'phone_number'=>'0123456781',
                'password'=>Hash::make('12345678'),
                'email_verified_at'=>Carbon::now(),
                'remember_token'=> Str::random(10),
                'role' => 3
            ],
            [
                'name' => 'Trần Bảo Long',
                'email' => 'longtb45670@fpt.edu.vn',
                'phone_number'=>'0123456782',
                'password'=>Hash::make('12345678'),
                'email_verified_at'=>Carbon::now(),
                'remember_token'=> Str::random(10),
                'role' => 3
            ],
            [
                'name' => 'Đỗ Thanh Hải',
                'email' => 'haidtph45039@fpt.edu.vn',
                'phone_number'=>'0123456783',
                'password'=>Hash::make('12345678'),
                'email_verified_at'=>Carbon::now(),
                'remember_token'=> Str::random(10),
                'role' => 3
            ],
            [
                'name' => 'Tống Trần Kiên',
                'email' => 'kienttph45089@fpt.edu.vn',
                'phone_number'=>'0123456784',
                'password'=>Hash::make('12345678'),
                'email_verified_at'=>Carbon::now(),
                'remember_token'=> Str::random(10),
                'role' => 3
            ],
            [
                'name' => 'Trần Kiều Ngọc Lan',
                'email' => 'lantknph45019@fpt.edu.vn',
                'phone_number'=>'0123456785',
                'password'=>Hash::make('12345678'),
                'email_verified_at'=>Carbon::now(),
                'remember_token'=> Str::random(10),
                'role' => 3
            ],
        ];
        DB::table('users')->insert($dataSeeder);
    }
}
