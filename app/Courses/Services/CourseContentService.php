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
        $courseContent = $course->content;

        $validated['title'] = $validated['sectionTitle'];
        $validated['type'] = $validated['sectionType'];
        $validated['content'] = $validated['sectionContent'];

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
        $courseContent = $course->content;

        $validated['type'] = $validated['sectionType'];
        $validated['title'] = $validated['sectionTitle'];

        $newSectionId = 1;
        if (!empty($courseContent)) {
            $newSectionId = end($courseContent)['section_id'] + 1;
        }
        $validated['section_id'] = $newSectionId;
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
