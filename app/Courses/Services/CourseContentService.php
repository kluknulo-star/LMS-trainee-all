<?php

namespace App\Courses\Services;

use App\Courses\Models\CourseItems;
use App\Courses\Repositories\CourseContentRepository;

class CourseContentService
{
    public function __construct(private CourseContentRepository $courseContentRepository)
    {
    }

    public function update($validated, $sectionId): bool
    {
        $courseContent['title'] = $validated['sectionTitle'];
        $courseContent['type_id'] = $validated['sectionType'];
        $courseContent['item_content'] = json_encode($validated['sectionContent']);
        return $this->courseContentRepository->update($sectionId, $courseContent);
    }

    public function store($validated, $courseId): CourseItems
    {
        $courseContent['course_id'] = $courseId;
        $courseContent['title'] = $validated['sectionTitle'];
        $courseContent['type_id'] = $validated['sectionType'];
        $courseContent['item_content'] = json_encode('');
        return $this->courseContentRepository->create($courseContent);
    }

    public function destroy($sectionId): bool
    {
        return $this->courseContentRepository->destroy($sectionId);
    }

    public function restore($sectionId): bool
    {
        return $this->courseContentRepository->restore($sectionId);
    }
}
