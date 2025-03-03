<?php

namespace Database\Factories;

use App\Models\TypeRealty;
use App\Models\TypeRent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RealtyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type_rent_id' => TypeRent::get()->random()->id,
            'type_realty_id' => TypeRealty::get()->random()->id,
            'address' => $this->faker->address(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'date_start' => $this->faker->date(),
            'date_end' => $this->faker->date(),
            'count_rooms' => $this->faker->numberBetween(1, 5),
            'total_square' => $this->faker->randomFloat(2, 10, 100),
            'living_square' => $this->faker->randomFloat(2, 10, 100),
            'kitchen_square' => $this->faker->randomFloat(2, 10, 100),
            'floor' => $this->faker->numberBetween(1, 25),
            'year_construction' => $this->faker->year(),
            'image' => $this->faker->imageUrl(),
            'description' => $this->faker->text(),
        ];
    }
}
