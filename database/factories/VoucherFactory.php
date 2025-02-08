<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code'=>fake()->unique()->numerify('######'),
            'discount_type'=>fake()->randomElement(['percentage','fixed']),
            'discount_value'=>rand(1,100),
            'total_usage'=>rand(1,30),
            'start_date'=>Carbon::now(),
            'end_date'=>Carbon::now()->addMonth(),
        ];
    }
}
