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
                'type_id' => TypeOfItems::get('type_id')->random()->type_id,
                'title' => fake()->text(90),
                'item_content' => '{}',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ];

            if($data[$i]['type_id'] == 1) {
                $data[$i]['item_content'] = json_encode(fake()->text);
            } elseif($data[$i]['type_id'] == 2) {
                $data[$i]['item_content'] = json_encode(fake()->domainName);
            } elseif ($data[$i]['type_id'] == 3) {
                $data[$i]['item_content'] = json_encode('TEST_ID');
            }
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            CourseItems::insert($chunk);
        }
    }
}
