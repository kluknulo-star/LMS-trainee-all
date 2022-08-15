<?php

namespace App\Courses\Services;

class CourseContentService
{
    public function __construct(private CourseService $courseService)
    {
    }

    public function getContent($courseId, $sectionId)
    {
        foreach ($this->courseService->getCourse($courseId, true)->content as $section) {
            if ($section['section_id'] == $sectionId) {
                return $section;
            }
        }
        return [];
    }

    public function edit($courseId)
    {
        if (!$this->courseService->checkOwn($courseId)) {
            return abort(403);
        }
    }

    public function update($validated, $courseId, $sectionId)
    {
        if (!$this->courseService->checkOwn($courseId)) {
            return abort(403);
        }
        $course = $this->courseService->getCourse($courseId, true);
        $courseContent = $this->courseService->getCourse($courseId, true)->content;
        $validated['section_id'] = $sectionId;
        $courseContent[$sectionId] = $validated;
        $course->content = json_encode($courseContent);
        return $course->save();
    }

    public function store($validated, $courseId)
    {
        if (!$this->courseService->checkOwn($courseId)) {
            return abort(403);
        }
        $course = $this->courseService->getCourse($courseId, true);
        $courseContent = $this->courseService->getCourse($courseId, true)->content;
        $validated['section_id'] = count($courseContent);
        $validated['title'] = $validated['sectionTitle'];
        $courseContent[$validated['section_id']] = $validated;
        $course->content = json_encode($courseContent);
        $course->save();
        return $validated['section_id'];
    }

    public function destroy($courseId, $sectionId)
    {
        if (!$this->courseService->checkOwn($courseId)) {
            return abort(403);
        }
        $course = $this->courseService->getCourse($courseId, true);
        $courseContent = $this->courseService->getCourse($courseId, true)->content;
        unset($courseContent[$sectionId]);
        $course->content = json_encode($courseContent);
        return $course->save();
    }
}
