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
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'slug' => $this->faker->slug,
            'sku' => $this->faker->ean13,
            'price' => $this->faker->randomFloat(2),
            'quantity' => $this->faker->randomDigit,
            'category_id' => $this->faker->numberBetween(1, 5)


        ];
    }
}
