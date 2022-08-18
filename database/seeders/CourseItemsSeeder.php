<?php

namespace Database\Seeders;

use App\Courses\Models\Course;
use App\Courses\Models\CourseItems;
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
                'item_content' => '{"0": {"section_id": "0", "type": "Article", "title": "'.fake()->text(70).'", "content": "'.fake()->text(2048).'"},
            "1": {"section_id": "1", "type": "YouTube Video", "title": "'.fake()->text(70).'", "content": "'.fake()->domainName.'"}}',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            CourseItems::insert($chunk);
        }
    }
}
