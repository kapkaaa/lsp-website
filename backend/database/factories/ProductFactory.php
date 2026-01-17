<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Brand;
use App\Models\Type;

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
            'name' => $this->faker->word,
            'selling_price' => $this->faker->numberBetween(10000, 1000000),
            'cost_price' => $this->faker->numberBetween(5000, 500000),
            'brand_id' => Brand::factory(),
            'type_id' => Type::factory(),
        ];
    }
}
