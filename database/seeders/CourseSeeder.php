<?php

namespace Database\Seeders;

use App\Courses\Models\Course;
use App\Users\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
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
                'title' => fake()->sentence(),
                'author_id' => User::where('is_teacher', true)->get('user_id')->random()->user_id,
                'description' => fake()->text(255),
                'content' => '{"0": {"section_id": "0", "type": "Article", "title": "'.fake()->text(70).'", "content": "'.fake()->text(2048).'"},
            "1": {"section_id": "1", "type": "YouTube Video", "title": "'.fake()->text(70).'", "content": "'.fake()->domainName.'"}}',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            Course::insert($chunk);
        }
    }
}
