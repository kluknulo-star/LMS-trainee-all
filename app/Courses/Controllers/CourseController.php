<?php

namespace App\Courses\Controllers;

use App\Courses\Models\AssignableCourse;
use App\Courses\Models\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Users\Models\User;
use Illuminate\Http\Request;


class CourseController extends Controller
{
    public function showAssignments(Request $request)
    {
        $recordsPerPage = 4;
        $searchParam = $request->input('search');
        $courses = auth()->user()
            ->assignedCourses()
            ->orderByDesc('course_id')
            ->search($searchParam)
            ->paginate($recordsPerPage);

        return view('pages.courses.assignments', compact('courses'));
    }

    public function showOwn(Request $request)
    {
        $recordsPerPage = 4;
        $searchParam = $request->input('search');
        $courses = auth()->user()
            ->courses()
            ->withTrashed()
            ->orderByDesc('course_id')
            ->search($searchParam)
            ->paginate($recordsPerPage);

        return view('pages.courses.own', compact('courses'));
    }

    public function assign(Request $request, int $courseId)
    {
        $action = $request->input('action');
        $userId = $request->input('user_id');

        switch ($action) {
            case 'assign':
                AssignableCourse::create([
                    'student_id' => $userId,
                    'course_id' => $courseId,
                ]);
                break;
            case 'deduct':
                AssignableCourse::where([
                    ['course_id', '=', $courseId],
                    ['student_id', '=', $userId],
                ])->delete();
                break;
        }

        return redirect()->route('courses.edit.assignments', ['id' => $courseId]);
    }

    public function play()
    {

    }

    public function edit(int $id)
    {
        $course = Course::findOrFail($id);
        return view('pages.courses.edit', compact('course'));
    }

    public function editAssignments(Request $request, int $courseId)
    {
        $recordsPerPage = 8;
        $state = $request->query('assign', 'already');
        $searchParam = $request->input('search');
        $users = Course::findOrFail($courseId)
            ->assignedUsers();

        if ($state != 'already') {
            $users = User::whereNotIn('user_id', $users->pluck('user_id')->toArray());
        }

        $users = $users
            ->orderByDesc('user_id')
            ->search($searchParam)
            ->paginate($recordsPerPage);

        return view('pages.courses.assign', compact('users', 'courseId', 'state'));
    }

    public function update(UpdateCourseRequest $request, int $id)
    {
        $validated = $request->validated();

        $course = Course::findOrFail($id);
        $course->update($validated);

        return redirect()->route('courses.own');
    }

    public function create()
    {
        return view('pages.courses.create');
    }

    public function store(CreateCourseRequest $request)
    {
        $validated  = $request->validated();
        $validated['author_id'] = auth()->id();

        Course::create($validated);
        return redirect()->route('courses.own');
    }

    public function destroy(int $id)
    {
        optional(Course::where('course_id', $id))->delete();
        return redirect()->route('courses.own');
    }

    public function restore(int $id)
    {
        optional(Course::withTrashed()->where('course_id', $id))->restore();
        return redirect()->route('courses.own');
    }

    public function statistics()
    {

    }
}
