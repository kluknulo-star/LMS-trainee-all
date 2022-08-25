<?php

namespace App\Courses\Controllers;

use App\Courses\Services\CourseService;
use App\Courses\Services\StatementService;
use App\Http\Controllers\Controller;
use App\Courses\Requests\CreateCourseRequest;
use App\Courses\Requests\UpdateCourseRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class CourseController extends Controller
{
    public function __construct(private CourseService $courseService, private StatementService $statementsService)
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
        if (empty($request->input('studentEmails'))) {
            $userId = $request->input('user_id');
            $this->courseService->assign($userId, $courseId);
        } else {
            $emails = preg_split('/\n|\r\n?/', $request->input('studentEmails'));
            $this->courseService->assignMany($emails, $courseId);
        }
        return redirect()->route('courses.edit.assignments', ['id' => $courseId, 'state' => 'all']);
    }

    public function deduct(Request $request, int $courseId): RedirectResponse
    {
        $userId = $request->input('user_id');
        $this->courseService->deduct($userId, $courseId);
        return redirect()->route('courses.edit.assignments', ['id' => $courseId, 'state' => 'already']);
    }

    public function play(int $courseId): View
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('view', [$course]);
        $user = auth()->user();
        $myCourseProgress = $this->statementsService->getStudentProgress($courseId, $user->email);
        return view('pages.courses.play', compact('course', 'myCourseProgress'));
    }

    public function edit(int $courseId): View
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('update', [$course]);
        return view('pages.courses.edit', compact('course'));
    }

    public function editAssignments(Request $request, int $courseId, string $state): View
    {
        $searchParam = $request->input('search');

        $course = $this->courseService->getCourse($courseId);
        $studentsProgress = [];


        if ($state == 'all') {
            $users = $this->courseService->getUnassignedUsers($searchParam, $courseId)->paginate(8);
        }

        if ($state == 'already') {
            $users = $this->courseService->getAssignedUsers($searchParam, $courseId)->paginate(8);
            $sectionsCourse = json_decode($course->content, true);

            foreach ($users as $user) {
                $progressStatements = $this->statementsService->getStudentProgress($courseId, $user->email);
                if (count($sectionsCourse)) {
                    $studentsProgress[$user->user_id] = round(count($progressStatements['passed']) / count($sectionsCourse) * 100);
                } else {
                    $studentsProgress[$user->user_id] = 0;
                }
            }
        }

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
        $this->authorize('delete', [$course]);
        $this->courseService->destroy($courseId);
        return redirect()->route('courses.own');
    }

    public function restore(int $courseId): RedirectResponse
    {
        $this->courseService->restore($courseId);
        return redirect()->route('courses.own');
    }

    public function statistics(int $courseId): View
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('update', [$course]);
        $count = [
            'CourseLaunched' => 9,
            'CoursePassed' => 9,
            'CourseAssigned' => 9,
            'SectionLaunched' => 9,
        ];
        return view('pages.courses.statistics', compact('count', 'courseId'));
    }
}
