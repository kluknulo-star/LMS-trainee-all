<?php

namespace App\Courses\Controllers;

use App\Courses\Requests\CreateCourseContentRequest;
use App\Courses\Requests\UpdateCourseContentRequest;
use App\Courses\Services\CourseContentService;
use App\Courses\Services\CourseService;
use App\Http\Controllers\Controller;

class CourseContentController extends Controller
{
    public function __construct(
        private CourseContentService $courseContentService,
        private CourseService $courseService,
    )
    {

    }

    public function edit($courseId, $sectionId)
    {
        $this->courseContentService->edit($courseId);
        $section = $this->courseContentService->getContent($courseId, $sectionId);
        return view('pages.courses.sections.edit', compact('section', 'courseId'));
    }

    public function update(UpdateCourseContentRequest $request, $courseId, $sectionId)
    {
        $validated = $request->validated();
        $this->courseContentService->update($validated, $courseId, $sectionId);
        return redirect()->route('courses.edit.section', [$courseId, $sectionId]);
    }

    public function store(CreateCourseContentRequest $request, $courseId)
    {
        $validated = $request->validated();
        $sectionId = $this->courseContentService->store($validated, $courseId);
        return redirect()->route('courses.edit.section', [$courseId, $sectionId]);
    }

    public function destroy($courseId, $sectionId)
    {
        $this->courseContentService->destroy($courseId, $sectionId);
        return redirect()->route('courses.edit', [$courseId]);
    }

    public function play($courseId, $sectionId)
    {
    }
}
