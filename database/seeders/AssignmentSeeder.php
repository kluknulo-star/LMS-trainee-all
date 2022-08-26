<?php

namespace Database\Seeders;

use App\Courses\Models\Assignment;
use App\Courses\Models\Course;
use App\Users\Models\User;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
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
                'student_id' => User::get('user_id')->random()->user_id,
                'course_id' => Course::get('course_id')->random()->course_id,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            Assignment::insert($chunk);
        }
    }
}
