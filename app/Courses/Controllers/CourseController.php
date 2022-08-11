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
    public function __construct(private CourseService $service)
    {

    }

    public function showAssignments(Request $request)
    {
        $searchParam = $request->input('search');
        $courses = $this->service->getAssignments($searchParam);
        return view('pages.courses.assignments', compact('courses'));
    }

    public function showOwn(Request $request)
    {
        $searchParam = $request->input('search');
        $courses = $this->service->getOwn($searchParam);
        return view('pages.courses.own', compact('courses'));
    }

    public function assign(Request $request, int $courseId)
    {
        $action = $request->input('action');
        $userId = $request->input('user_id');
        $this->service->assign($userId, $courseId, $action);
        return redirect()->route('courses.edit.assignments', ['id' => $courseId]);
    }

    public function play()
    {

    }

    public function edit(int $id)
    {
        $course = $this->service->getCourse($id);
        return view('pages.courses.edit', compact('course'));
    }

    public function editAssignments(Request $request, int $courseId)
    {
        $state = $request->query('assign', 'already');
        $searchParam = $request->input('search');
        $users = $this->service->editAssignments($state, $searchParam, $courseId);
        return view('pages.courses.assign', compact('users', 'courseId', 'state'));
    }

    public function update(UpdateCourseRequest $request, int $id)
    {
        $validated = $request->validated();
        $this->service->update($id, $validated);
        return redirect()->route('courses.own');
    }

    public function create()
    {
        return view('pages.courses.create');
    }

    public function store(CreateCourseRequest $request)
    {
        $validated  = $request->validated();
        $this->service->store($validated);
        return redirect()->route('courses.own');
    }

    public function destroy(int $id)
    {
        $this->service->destroy($id);
        return redirect()->route('courses.own');
    }

    public function restore(int $id)
    {
        $this->service->restore($id);
        return redirect()->route('courses.own');
    }

    public function statistics()
    {

    }
}
