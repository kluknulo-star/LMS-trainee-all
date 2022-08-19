<?php

namespace App\Courses\Controllers;

use App\Courses\Services\CourseService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;


class CourseController extends Controller
{
    public function __construct(private CourseService $courseService)
    {

    }

    public function showAssignments(Request $request): View
    {
        $searchParam = $request->input('search');
        $courses = $this->courseService->getAssignments($searchParam)->paginate(4);
        return view('pages.courses.assignments', compact('courses'));
    }

    public function showOwn(Request $request): View
    {
        $searchParam = $request->input('search');
        $courses = $this->courseService->getOwn($searchParam)->paginate(4);
        return view('pages.courses.own', compact('courses'));
    }

    public function assign(Request $request, int $courseId): RedirectResponse
    {
        $userId = $request->input('user_id');
        $this->courseService->assign($userId, $courseId);
        return redirect()->route('courses.edit.assignments', ['id' => $courseId]);
    }

    public function deduct(Request $request, int $courseId): RedirectResponse
    {
        $userId = $request->input('user_id');
        $this->courseService->deduct($userId, $courseId);
        return redirect()->route('courses.edit.assignments', ['id' => $courseId]);
    }

    public function play(int $courseId): View
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('view', [$course]);

        $user = auth()->user();
        $myCourseProgress = $this->courseService->getStudentProgress($courseId, $user->email);
//        dd($myCourseProgress);

        return view('pages.courses.play', compact('course', 'myCourseProgress'));
    }

    public function edit(int $courseId): View
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('update', [$course]);
        return view('pages.courses.edit', compact('course'));
    }

    public function editAssignments(Request $request, int $courseId): View
    {
        $state = $request->query('assign', 'already');
        $searchParam = $request->input('search');
        if ($state == 'all') $users = $this->courseService->getUnassignedUsers($searchParam, $courseId)->paginate(8);
        if ($state == 'already') $users = $this->courseService->getAssignedUsers($searchParam, $courseId)->paginate(8);

        $course = $this->courseService->getCourse($courseId);
        $sectionsCourse = json_decode($course->content, true);
        $studentsProgress = [];
        foreach ($users as $user) {
            $progressStatements = $this->courseService->getStudentProgress($courseId, $user->email);
            if (count($sectionsCourse)) {
                $studentsProgress[$user->user_id] = round(count($progressStatements['passed']) / count($sectionsCourse) * 100);
            } else
                $studentsProgress[$user->user_id] = 0;
        }
//        dd($studentsProgress);

        return view('pages.courses.assign', compact('users', 'courseId', 'state', 'studentsProgress'));
    }

    public function update(UpdateCourseRequest $request, int $courseId): RedirectResponse
    {
        $validated = $request->validated();
        $this->courseService->update($courseId, $validated);
        return redirect()->route('courses.own');
    }

    public function create(): View
    {
        $this->authorize('create', [auth()->user()]);
        return view('pages.courses.create');
    }

    public function store(CreateCourseRequest $request): RedirectResponse
    {
        $this->authorize('create', [auth()->user()]);
        $validated = $request->validated();
        $this->courseService->store($validated);
        return redirect()->route('courses.own');
    }

    public function destroy(int $courseId): RedirectResponse
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('delete', [auth()->user(), $course]);
        $this->courseService->destroy($courseId);
        return redirect()->route('courses.own');
    }

    public function restore(int $courseId): RedirectResponse
    {
        $this->courseService->restore($courseId);
        return redirect()->route('courses.own');
    }

    public function statistics(int $courseId)
    {
        $count = [
            'CourseLaunched' => 9,
            'CoursePassed' => 9,
            'CourseAssigned' => 9,
            'SectionLaunched' => 9,
        ];
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('update', [$course]);
        return view('pages.courses.statistics', compact('count', 'courseId'));
    }
}
