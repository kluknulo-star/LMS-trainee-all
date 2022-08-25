<?php

namespace App\Console\Commands;

use App\Courses\Models\Course;
use App\Courses\Models\CourseItems;
use App\Courses\Models\TypeOfItems;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class ContentTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:contentTransfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer data from the content column to the course_items table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $courses = Course::withTrashed()->get();
        $types =  TypeOfItems::all();

        foreach($courses as $course) {
            foreach (json_decode($course['all_content']) as $item) {
                $typeId = optional($types->where('type', $item->type)->first())->getKey();

                if(!empty($course->course_id) && !empty($typeId) && !empty($item->title) && !empty($item->all_content)) {
                    CourseItems::insert([
                        'course_id' => $course->course_id,
                        'type_id' => $typeId,
                        'title' => $item->title,
                        'item_content' => json_encode($item->all_content),
                    ]);
                }
            }
        }
    }
}
