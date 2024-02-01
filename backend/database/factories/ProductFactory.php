<?php

namespace Database\Factories;

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
        // Get existing Brand and City records from the database
        $brand = \App\Models\Brand::inRandomOrder()->first();
        $city = \App\Models\City::inRandomOrder()->first();

        // If no records exist, create new ones
        if (!$brand) {
            $brand = \App\Models\Brand::factory()->create();
        }

        if (!$city) {
            $city = \App\Models\City::factory()->create();
        }

        return [
            'NAME_PRODUCT' => $this->faker->name,
            'PRICE' => $this->faker->randomFloat(2, 0, 8),
            'BRAND_PRODUCT' => $brand->COD_BRAND,
            'STOCK' => $this->faker->randomNumber(2),
            'CITY' => $city->COD_CITY,
        ];
    }
}
