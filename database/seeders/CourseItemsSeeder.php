<?php

namespace Database\Seeders;

use App\Courses\Models\Course;
use App\Courses\Models\CourseItems;
use App\Courses\Models\TypeOfItems;
use Illuminate\Database\Seeder;

class CourseItemsSeeder extends Seeder
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
                'course_id' => Course::get('course_id')->random()->course_id,
                'type_id' => TypeOfItems::where('type', 'Article')->get('type_id'),
                'title' => fake()->text(90),
                'item_content' => json_encode(fake()->text),
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            CourseItems::insert($chunk);
        }
    }
}
