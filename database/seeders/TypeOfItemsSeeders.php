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
        TypeOfItems::insert(['type' => 'Article']);
        TypeOfItems::insert(['type' => 'YouTubeVideoLink']);
        TypeOfItems::insert(['type' => 'Test']);
    }
}
