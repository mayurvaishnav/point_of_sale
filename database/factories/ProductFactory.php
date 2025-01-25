<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'category_id' => fake()->numberBetween(1,10),
            'supplier_id' => fake()->numberBetween(1,10),
            'code' => fake()->word(),
            'garage' => fake()->word(),
            'store' => fake()->numberBetween(1,10),
            'tax_rate' => fake()->randomElement([0, 13, 30]),
            'buying_price' => fake()->randomFloat(2, 1, 100),
            'selling_price' => fake()->randomFloat(2, 1, 100),
            'tax' => fake()->randomFloat(2, 1, 100),
            'quantity' => fake()->numberBetween(1,100),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
