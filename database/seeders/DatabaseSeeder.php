<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AddressUser;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\Skus;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherUser;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //seeder
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AddressUserSeeder::class);

        //factory
        User::factory(50)->create();
        AddressUser::factory(50)->create();
        Brand::factory(10)->create();
        Category::factory(20)->create();
        Product::factory(100)->create();

        // $this->call(ProductAtributeSeeder::class);
        // $this->call(ProductAtributeValueSeeder::class);

        Skus::factory(150)->create();

        $this->call(PaymentMethodStatusSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(ShippingMethodSeeder::class);

        Voucher::factory(50)->create();
        VoucherUser::factory(50)->create();

        $this->call(OrderStatusSeeder::class);

        Post::factory(30)->create();

    }
}
