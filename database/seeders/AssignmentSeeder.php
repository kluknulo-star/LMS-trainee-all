<?php

namespace Database\Seeders;

use App\Courses\Models\Course;
use App\Users\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recordCount = 500;
        for($counter = 1; $counter <= $recordCount; $counter++) {
            DB::table('assignments')->insert(
                [
                    'student_id' => User::get('user_id')->random()->user_id,
                    'course_id' => Course::get('course_id')->random()->course_id,
                ]
            );
        }
    }
}
