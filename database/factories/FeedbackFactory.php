<?php

namespace Database\Factories;

use App\Models\Realty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::get()->random()->id,
            'realty_id' => Realty::get()->random()->id,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->realText(),
        ];
    }
}
