<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'base_price' => $this->faker->randomFloat(2, 500, 100000),
            'unit' => $this->faker->randomElement(['lembar', 'pcs', 'lusin']),
            'is_active' => true,
            'requires_file_upload' => $this->faker->boolean(),
        ];
    }
}
