<?php

namespace Database\Factories;

use App\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Users\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'surname' => fake()->lastName,
            'name' => fake()->firstName,
            'patronymic' => fake()->lastName,
            'email' => rand(1, 1000).fake()->email,
            'email_verified_at' => now(),
            'password' => '$2y$10$G7QWrbOfjIQBXj2y6UdTbO8erlytx7ZEYSiGJ1tN03QCB0BQ2eO1G',
            'remember_token' => Str::random(20),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
