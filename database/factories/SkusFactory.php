<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skus>
 */
class SkusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id'=>rand(1,10),
            'name'=>fake()->name(),
            'quantity'=>10,
            'price'=>rand(100000,1000000),
            'barcode'=>fake()->numerify("#####"),
        ];
    }
}
