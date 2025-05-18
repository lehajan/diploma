<?php

namespace Database\Factories;

use App\Models\Realty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
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
