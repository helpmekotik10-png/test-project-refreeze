<?php

namespace Database\Factories;

use App\Models\Price;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Price>
 */
class PriceFactory extends Factory
{
    protected function withFaker(): \Faker\Generator
    {
        return \Faker\Factory::create('ru_RU');
    }
    
    public function definition(): array
    {
        return [
            'id_product' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'price' => $this->faker->numberBetween(100, 50000), // от 100 до 50 000
        ];
    }
}
