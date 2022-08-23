<?php

namespace App\Courses\Services;

use App\Courses\Models\CourseItems;

class CourseContentService
{
    public function __construct(private CourseService $courseService)
    {
    }

//    public function getContent($courseId, $sectionId): array
//    {
//        foreach ($this->courseService->getCourse($courseId, true)->content as $section) {
//            if ($section['section_id'] == $sectionId) {
//                return $section;
//            }
//        }
//        return [];
//    }

    public function update($validated, $course, $sectionId): bool
    {
        $courseContent['title'] = $validated['sectionTitle'];
        $courseContent['type_id'] = $validated['sectionType'];
        $courseContent['item_content'] = json_encode($validated['sectionContent']);
        return CourseItems::where('item_id', $sectionId)->update($courseContent);
    }

    public function store($validated, $courseId): CourseItems
    {
        $courseContent['course_id'] = $courseId;
        $courseContent['title'] = $validated['sectionTitle'];
        $courseContent['type_id'] = $validated['sectionType'];
        $courseContent['item_content'] = json_encode('');
        return CourseItems::create($courseContent);
    }

    public function destroy($courseId, $sectionId): bool
    {
        return CourseItems::findOrFail($sectionId)->delete();
    }
}
