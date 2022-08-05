<?php

namespace Database\Factories;

use App\Courses\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Courses\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->company(),
            'author_id' => 1, //rand(1, *максимальный id пользователя*) количество пользователей созданные фабрикой
            'description' => fake()->text(255),
            'content' => '{"0": {"type": "text", "content": "text"},
            "1": {"type": "link", "content": "https://www.youtube.com/"}}',
        ];
    }
}
