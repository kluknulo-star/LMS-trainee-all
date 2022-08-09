<?php

namespace App\Courses\Controllers;

use App\Courses\Models\Course;
use App\Http\Controllers\Controller;
use App\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $recordsPerPage = 8;

        $courses = Course::orderBy('course_id', 'desc')
            ->paginate($recordsPerPage);

        return view('pages/courses/index', ['courses' => $courses]);
    }

    public function showOwn()
    {
        $recordsPerPage = 8;

        $courses = Course::orderBy('course_id', 'desc')
            ->where('author_id', Auth::id())
            ->paginate($recordsPerPage);

        return view('pages/courses/own', ['courses' => $courses]);
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
