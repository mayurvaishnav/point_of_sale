<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_name' => fake()->word(),
            'category_id' => fake()->numberBetween(1,10),
            'sub_category_id' => fake()->numberBetween(1,10),
            'supplier_id' => fake()->numberBetween(1,10),
            'product_code' => fake()->word(),
            'product_garage' => fake()->word(),
            'product_store' => fake()->numberBetween(1,10),
            'buying_price' => fake()->randomFloat(2, 1, 100),
            'selling_price' => fake()->randomFloat(2, 1, 100),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
