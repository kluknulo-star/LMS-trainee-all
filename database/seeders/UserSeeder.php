<?php

namespace Database\Seeders;

use App\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $recordCount = 500;

        for ($i = 0; $i < $recordCount; $i++) {
            $data[] = [
                'surname' => fake()->lastName,
                'name' => fake()->firstName,
                'patronymic' => fake()->lastName,
                'email' => rand(1, 1000).fake()->email, //rand is salt
                'email_confirmed_at' => now(),
                'password' => '$2y$10$G7QWrbOfjIQBXj2y6UdTbO8erlytx7ZEYSiGJ1tN03QCB0BQ2eO1G',
                'is_teacher' => rand(0, 1),
                'remember_token' => Str::random(20),
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            User::insert($chunk);
        }
    }
}
