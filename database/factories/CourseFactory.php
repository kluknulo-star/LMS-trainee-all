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
            'author_id' => rand(1, 20), //пользователей созданные фабрикой пользователи
            'description' => fake()->text(255),
            'content' => '{"0": {"type": "text", "content": "text"},
            "1": {"type": "link", "content": "https://www.youtube.com/"}}',
        ];
    }
}
