<?php

namespace Database\Seeders;

use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            //The seeders are arranged in order of dependence
            UserSeeder::class,
            CourseSeeder::class,
            TypeOfItemsSeeders::class,
            CourseItemsSeeder::class,
            ItemsStatsSeeders::class,
            AssignmentSeeder::class,
        ]);
    }
}
