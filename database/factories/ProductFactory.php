<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(), 
            'description' => fake()->sentence(), 
            'price' => fake()->randomFloat(2,1,100), 
            'stock' => fake()->numberBetween(1,50), 
            'category_id' => Category::factory(), 
            'image' => fake()->imageUrl(480,480,'products'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
