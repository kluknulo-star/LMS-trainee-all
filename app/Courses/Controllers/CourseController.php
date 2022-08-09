<?php

namespace App\Courses\Controllers;

use App\Courses\Models\Course;
use App\Http\Controllers\Controller;
use App\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function showAssignments(Request $request)
    {
        $searchParam = $request->input('search');
        $recordsPerPage = 8;

        $courses = Course::orderBy('course_id', 'desc')
            ->search($searchParam)
            ->paginate($recordsPerPage);

        return view('pages.courses.assignments', ['courses' => $courses]);
    }

    public function showOwn(Request $request)
    {
        $searchParam = $request->input('search');
        $recordsPerPage = 2;

        $courses = User::find(Auth::id())
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

    public function edit()
    {
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
