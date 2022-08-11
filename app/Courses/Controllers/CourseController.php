<?php

namespace App\Courses\Controllers;

use App\Courses\Models\AssignableCourse;
use App\Courses\Models\Course;
use App\Courses\Services\CourseService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Users\Models\User;
use Illuminate\Http\Request;


class CourseController extends Controller
{
    public function __construct(private CourseService $courseService)
    {

    }

    public function showAssignments(Request $request)
    {
        $searchParam = $request->input('search');
        $courses = $this->courseService->getAssignments($searchParam)->paginate(4);
        return view('pages.courses.assignments', compact('courses'));
    }

    public function showOwn(Request $request)
    {
        $searchParam = $request->input('search');
        $courses = $this->courseService->getOwn($searchParam)->paginate(4);
        return view('pages.courses.own', compact('courses'));
    }

    public function assign(Request $request, int $courseId)
    {
        $action = $request->input('action');
        $userId = $request->input('user_id');
        $this->courseService->assign($userId, $courseId, $action);
        return redirect()->route('courses.edit.assignments', ['id' => $courseId]);
    }

    public function play(int $id)
    {
        $course = $this->courseService->getCourse($id);
        return view('pages.courses.play', compact('course'));
    }

    public function edit(int $id)
    {
        $course = $this->courseService->getCourse($id);
        return view('pages.courses.edit', compact('course'));
    }

    public function editAssignments(Request $request, int $courseId)
    {
        $state = $request->query('assign', 'already');
        $searchParam = $request->input('search');
        $users = $this->courseService->editAssignments($state, $searchParam, $courseId)->paginate(8);
        return view('pages.courses.assign', compact('users', 'courseId', 'state'));
    }

    public function update(UpdateCourseRequest $request, int $id)
    {
        $validated = $request->validated();
        $this->courseService->update($id, $validated);
        return redirect()->route('courses.own');
    }

    public function create()
    {
        return view('pages.courses.create');
    }

    public function store(CreateCourseRequest $request)
    {
        $validated = $request->validated();
        $this->courseService->store($validated);
        return redirect()->route('courses.own');
    }

    public function destroy(int $id)
    {
        $this->courseService->destroy($id);
        return redirect()->route('courses.own');
    }

    public function restore(int $id)
    {
        $this->courseService->restore($id);
        return redirect()->route('courses.own');
    }

    public function statistics()
    {

    }
}
