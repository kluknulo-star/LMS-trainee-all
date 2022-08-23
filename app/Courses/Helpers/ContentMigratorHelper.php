<?php

namespace App\Courses\Helpers;

use App\Courses\Models\Course;
use App\Courses\Models\CourseItems;
use App\Courses\Models\TypeOfItems;

class ContentMigratorHelper
{
    public function coursesDataMigrate(): bool
    {
        $courses = Course::withTrashed()->get();

        foreach($courses as $course) {
            $courseId = $course['course_id'];

            foreach (json_decode($course['content']) as $item) {
                $itemType = $item->type;
                $typeOfItem = TypeOfItems::firstOrCreate(
                    ['type' => $itemType]
                );
                $itemTitle = $item->title;
                $itemContent = $item->content;

                CourseItems::create([
                    'course_id' => $courseId,
                    'type_id' => $typeOfItem->type_id,
                    'title' => $itemTitle,
                    'item_content' => json_encode($itemContent),
                ]);
            }
        }

        return true;
    }

    public function coursesDataMigrateRollBack(): bool
    {
        $items = CourseItems::all();
        foreach ($items as $item) {
            $courseId = $item->course_id;
            $itemType = TypeOfItems::where('type_id', $item->type_id)->first()->type;
            $itemTitle = $item->title;
            $itemContent = json_decode($item->item_content, TRUE);

            $course = Course::where('course_id', $courseId)->first();
            $content = json_decode($course->content, TRUE);
            $newItem = [
                'type' => $itemType,
                'title' => $itemTitle,
                'content' => $itemContent,
                'section_id' => count($content),
            ];
            $content[] = $newItem;
            $course->content = json_encode($content);
            $course->save();
        }

        return true;
    }
}
