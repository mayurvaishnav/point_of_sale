<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_id' => fake()->optional(0.5)->randomElement(Category::pluck('id')->toArray()),
            'name' => fake()->name,
            'slug' => fake()->slug,
            'description' => fake()->paragraph,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}