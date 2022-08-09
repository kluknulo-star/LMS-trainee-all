<?php

namespace Database\Seeders;

use App\Users\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //User default password - 'Pass-word12345'
        $recordTeacherCount = 100;
        User::factory($recordTeacherCount)->create([
            'surname' => 'TeacherAdmin', 'patronymic' => null, 'is_teacher' => true,
        ]);

        $recordStudentCount = 500;
        User::factory($recordStudentCount)->create();
    }
}
