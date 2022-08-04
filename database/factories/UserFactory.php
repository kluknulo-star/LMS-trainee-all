<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $randNumber = rand(1, 100000);
        return [
            'surname' => 'TestSurname'.$randNumber,
            'name' => 'TestName'.$randNumber,
            'patronymic' => 'TestPatronymic'.$randNumber,
            'email' => $randNumber.fake()->email,
            'email_verified_at' => now(),
            'password' => '$2y$10$72YQnx/ElM/UviWSGyL5MeoAF9AJimNCIFcME466wzC68fwDSMIpm', //default password - 'password'
            'remember_token' => Str::random(10),
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
