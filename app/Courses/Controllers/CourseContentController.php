<?php

namespace App\Courses\Controllers;

use App\Courses\Requests\CreateCourseContentRequest;
use App\Courses\Requests\UpdateCourseContentRequest;
use App\Courses\Services\CourseContentService;
use App\Courses\Services\CourseService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CourseContentController extends Controller
{
    public function __construct(
        private CourseContentService $courseContentService,
        private CourseService $courseService,
    )
    {
    }

    public function edit($courseId, $sectionId): View
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('update', [$course]);
        $section = $course->content->where('item_id', $sectionId)->first();
        return view('pages.courses.sections.edit', compact('section', 'courseId'));
    }

    public function update(UpdateCourseContentRequest $request, $courseId, $sectionId): RedirectResponse
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('update', [$course]);
        $validated = $request->validated();
        $this->courseContentService->update($validated, $sectionId);
        return redirect()->route('courses.edit', [$courseId])
                             ->with(['success' => __('success.'.__FUNCTION__.'CourseContent')]);
    }

    public function store(CreateCourseContentRequest $request, $courseId): RedirectResponse
    {
        $this->authorize('create', [auth()->user()]);
        $validated = $request->validated();
        $sectionId = $this->courseContentService->store($validated, $courseId);
        return redirect()->route('courses.edit.section', [$courseId, $sectionId])
                             ->with(['success' => __('success.'.__FUNCTION__.'CourseContent')]);
    }

    public function destroy($courseId, $sectionId): RedirectResponse
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('delete', [$course]);
        $this->courseContentService->destroy($sectionId);
        return redirect()->route('courses.edit', [$courseId])
                             ->with(['success' => __('success.'.__FUNCTION__.'CourseContent')]);
    }

    public function restore($courseId, $sectionId)
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('restore', [$course]);
        $this->courseContentService->restore($sectionId);
        return redirect()->route('courses.edit', [$courseId])
                             ->with(['success' => __('success.'.__FUNCTION__.'CourseContent')]);
    }
}
