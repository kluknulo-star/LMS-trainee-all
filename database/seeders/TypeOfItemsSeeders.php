<?php

namespace Database\Seeders;

use App\Courses\Models\TypeOfItems;
use Illuminate\Database\Seeder;

class TypeOfItemsSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeOfItems::insert(['type' => 'Article', 'created_at' => NOW(), 'updated_at' => NOW()]);
        TypeOfItems::insert(['type' => 'YouTubeVideoLink', 'created_at' => NOW(), 'updated_at' => NOW()]);
        TypeOfItems::insert(['type' => 'Test', 'created_at' => NOW(), 'updated_at' => NOW()]);
    }
}
