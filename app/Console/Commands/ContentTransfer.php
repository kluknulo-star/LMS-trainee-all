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

        foreach($courses as $course) {
            $courseId = $course['course_id'];

            foreach (json_decode($course['all_content']) as $item) {
                $itemType = $item->type;
                $typeOfItem = TypeOfItems::firstOrCreate(
                    ['type' => $itemType]
                );
                $itemTitle = $item->title;
                $itemContent = $item->all_content;

                $newItem = CourseItems::create([
                    'course_id' => $courseId,
                    'type_id' => $typeOfItem->type_id,
                    'title' => $itemTitle,
                    'item_content' => json_encode($itemContent),
                ]);
                if($newItem) {
                    DB::table('courses')->where('course_id', $courseId)->update(['all_content' => new Expression('(JSON_ARRAY())')]);
                }
            }
        }
    }
}
