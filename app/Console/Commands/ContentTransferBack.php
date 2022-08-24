<?php

namespace App\Console\Commands;

use App\Courses\Models\Course;
use App\Courses\Models\CourseItems;
use App\Courses\Models\TypeOfItems;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ContentTransferBack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:contentTransferBack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move the data from the course_itemstable table to the content column';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $items = CourseItems::all();
        foreach ($items as $item) {
            $itemId = $item->item_id;
            $courseId = $item->course_id;
            $itemType = TypeOfItems::where('type_id', $item->type_id)->first()->type;
            $itemTitle = $item->title;
            $itemContent = json_decode($item->item_content, TRUE);

            $course = Course::where('course_id', $courseId)->first();
            $content = json_decode($course->all_content, TRUE);
            $newItem = [
                'type' => $itemType,
                'title' => $itemTitle,
                'all_content' => $itemContent,
                'section_id' => count($content),
            ];
            $content[] = $newItem;
            $course->all_content = json_encode($content);
            $course->save();
            if($newItem) {
                DB::table('course_items')->where('item_id', $itemId)->delete();
            }
        }
    }
}
