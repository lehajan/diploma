<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Faker\Generator as Faker;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserFactory extends Factory
{

    protected static ?string $password;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('ru_RU');

        return [
            'name' => $faker->firstName(),
            'surname' => $faker->lastname(),
            'patronymic' => $faker->firstName('male') . 'ович',
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
