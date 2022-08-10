<?php

namespace App\Courses\Controllers;

use App\Courses\Models\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function showAssignments(Request $request)
    {
        $searchParam = $request->input('search');
        $recordsPerPage = 4;
        $coursesIds = DB::table('assignments')
            ->where('student_id', auth()->id())
            ->orderBy('course_id', 'desc')
            ->pluck('course_id');

        $courses = Course::whereIn('course_id', $coursesIds)
            ->orderByDesc('course_id')
            ->search($searchParam)
            ->paginate($recordsPerPage);

        return view('pages.courses.assignments', compact('courses'));
    }

    public function showOwn(Request $request)
    {
        $searchParam = $request->input('search');
        $recordsPerPage = 4;
        $courses = auth()->user()
            ->courses()
            ->withTrashed()
            ->orderByDesc('course_id')
            ->search($searchParam)
            ->paginate($recordsPerPage);

        return view('pages.courses.own', compact('courses'));
    }

    public function assign()
    {

    }

    public function play()
    {

    }

    public function edit(int $id)
    {
        $course = Course::findOrFail($id);
        return view('pages.courses.edit', compact('course'));
    }

    public function editAssignments(Request $request, int $id)
    {
        $stateForView = $request->query('assign', 'already');
        $courseId = $id;
        $recordsPerPage = 8;
        $usersIdsForView = DB::table('assignments')
            ->where('course_id', $id)
            ->orderBy('student_id', 'desc')
            ->pluck('student_id');

        if ($stateForView == 'all') {
            $usersIdsAll = User::all()->pluck('user_id');
            $usersIdsForView = array_diff($usersIdsAll->toArray(), $usersIdsForView->toArray());
        }

        $users = User::whereIn('user_id', $usersIdsForView)
            ->orderByDesc('user_id')
            ->paginate($recordsPerPage);

        return view('pages.courses.assign', compact('users', 'courseId'));
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
