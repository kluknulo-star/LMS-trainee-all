<?php

namespace App\Courses\Controllers;

use App\Courses\Models\Course;
use App\Http\Controllers\Controller;
use App\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function destroy()
    {

    }

    public function editAssignments()
    {

    }

    public function update()
    {

    }

    public function create()
    {
        return view('pages.courses.create');
    }

    public function store()
    {

    }

    public function restore()
    {

    }

    public function statistics()
    {

    }
}
