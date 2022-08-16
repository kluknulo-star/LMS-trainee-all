<?php

namespace Database\Factories;

use App\Courses\Models\Course;
use App\Users\Models\User;
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
            'title' => fake()->sentence(),
            'author_id' => User::where('is_teacher', true)->get('user_id')->random()->user_id,
            'description' => fake()->text(255),
            'content' => '{"0": {"section_id": "0", "type": "Article", "title": "'.fake()->text(70).'", "content": "'.fake()->text(2048).'"},
            "1": {"section_id": "1", "type": "YouTube Video", "title": "'.fake()->text(70).'", "content": "'.fake()->domainName.'"}}',
        ];
    }
}
