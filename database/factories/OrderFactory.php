<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice' => $this->faker->unique()->slug(),
            'slug' => $this->faker->unique()->slug(),
            'user_id' => 1,
            'plant_id' => $this->faker->numberBetween(1, 4),
            'quantity' => $this->faker->numberBetween(1, 10),
            'total' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement(['pending', 'success', 'annulled']),
        ];
    }
}
