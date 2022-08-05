<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Courses\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $randNumber = rand(1000, 9999);
        return [
            'title' => 'TestTitle'.$randNumber,
            'author_id' => rand(1, 20), //пользователей созданные фабрикой пользователи
            'description' => 'TestDescription'.$randNumber,
            'content' => '{"0": {"type": "text", "content": "text"},
            "1": {"type": "link", "content": "https://www.youtube.com/"}}',
        ];
    }
}
