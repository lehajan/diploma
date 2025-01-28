<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ApartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'count_rooms' => $this->faker->numberBetween(1, 5),
            'square' => $this->faker->randomElement
            ([
                '36.6',
                '27.9',
                '31.1',
                '65.5',
                '47.7'
            ]),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'address' => $this->faker->address(),
            'description' => $this->faker->text(),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
