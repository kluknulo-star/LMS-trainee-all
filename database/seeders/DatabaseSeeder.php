<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $recordCount = 20; //количество записей на заполнение

        User::factory()->create([
            'surname' => 'TestAdminSurname',
            'name' => 'TestAdminName',
            'patronymic' => 'TestAdminPatronymic',
            'is_teacher' => true,
        ]);
        User::factory($recordCount)->create();
        //User default password - 'password'

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
