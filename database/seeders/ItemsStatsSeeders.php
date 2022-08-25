<?php

namespace Database\Seeders;

use App\Courses\Models\Course;
use App\Courses\Models\CourseItems;
use App\Courses\Models\ItemsStats;
use App\Courses\Models\TypeOfItems;
use App\Users\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemsStatsSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $recordCount = 500;
        $status = ['passed', 'launched', 'failed'];

        for ($i = 0; $i < $recordCount; $i++) {
            $data[] = [
                'user_id' => User::get('user_id')->random()->user_id,
                'item_id' => CourseItems::get('item_id')->random()->item_id,
                'status' => $status[array_rand($status, 1)],
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            ItemsStats::insert($chunk);
        }
    }
}
