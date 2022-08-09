<?php

namespace Database\Seeders;

use App\Courses\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $recordCount = 500;
        Course::factory($recordCount)->create();
    }
}
