<?php

namespace Database\Seeders;

use App\Courses\Models\Course;
use App\Users\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $recordCount = 500;
//        Course::factory($recordCount)->create();

        $data = [];
        $recordCount = 500;

        for ($i = 0; $i < $recordCount; $i++) {
            $data[] = [
                'title' => fake()->text(90),
                'author_id' => User::where('is_teacher', true)->get('user_id')->random()->user_id,
                'description' => fake()->text(255),
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            Course::insert($chunk);
        }
    }
}
