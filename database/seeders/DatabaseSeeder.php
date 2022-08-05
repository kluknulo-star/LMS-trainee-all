<?php

namespace Database\Seeders;

use App\Courses\Models\Course;
use App\Users\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'surname' => 'Test',
            'name' => 'Admin',
            'patronymic' => 'Teacher',
            'email' => 'testadmin@gmail.com',
            'is_teacher' => true,
        ]);
        User::factory(10000)->create();
        //User default password - 'password'

        $recordCount = 20; //количество записей на заполнение
        Course::factory($recordCount)->create();

        //AssignmentFactory
        for($i = 1; $i <= $recordCount; $i++) {
            DB::table('assignments')->insert(
                [
                    'student_id' => rand(1, $recordCount), //пользователи созданные фабрикой пользователи
                    'course_id' => rand(1, $recordCount), //курсы созданные фабрикой пользователи
                ]
            );
        }
    }
}
