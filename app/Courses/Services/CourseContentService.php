<?php

namespace App\Courses\Services;

class CourseContentService
{
    public function __construct(private CourseService $courseService)
    {
    }

    public function getContent($courseId, $sectionId): array
    {
        foreach ($this->courseService->getCourse($courseId, true)->content as $section) {
            if ($section['section_id'] == $sectionId) {
                return $section;
            }
        }
        return [];
    }

    public function update($validated, $courseId, $sectionId): bool
    {
        $course = $this->courseService->getCourse($courseId, true);
        $courseContent = array_values($course->content);

        $validated['title'] = $validated['sectionTitle'];
        $validated['type'] = $validated['sectionType'];
        $validated['content'] = $validated['sectionContent'];
        $validated['section_id'] = $sectionId;
        unset($validated['sectionType']);
        unset($validated['sectionTitle']);
        unset($validated['sectionContent']);

        for ($i = 0; $i < count($courseContent); $i++) {
            if ($courseContent[$i]['section_id'] == $sectionId) {
                $courseContent[$i] = $validated;
            }
        }

        $course->content = json_encode($courseContent);
        $course->save();

        return $course->save();
    }

    public function store($validated, $courseId): int
    {
        $course = $this->courseService->getCourse($courseId, true);
        $courseContent = $course->content;

        $newSectionId = 0;
        if (!empty($courseContent)) {
            $newSectionId = end($courseContent)['section_id']+1;
        }
        $validated['section_id'] = $newSectionId;
        $validated['title'] = $validated['sectionTitle'];
        $validated['type'] = $validated['sectionType'];
        unset($validated['sectionType']);
        unset($validated['sectionTitle']);

        array_push($courseContent, $validated);
        $course->content = json_encode($courseContent);
        $course->save();

        return $validated['section_id'];
    }

    public function destroy($courseId, $sectionId): bool
    {
        $course = $this->courseService->getCourse($courseId, true);
        $courseContent = array_values($course->content);
        for ($i = 0; $i < count($courseContent); $i++) {
            if ($courseContent[$i]['section_id'] == $sectionId) {
                unset($courseContent[$i]);
            }
        }

        $course->content = json_encode($courseContent);
        return $course->save();
    }
}
