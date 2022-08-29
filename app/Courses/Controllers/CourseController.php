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

class CourseController extends Controller
{
    public function __construct(private CourseService $courseService, private StatementService $statementsService)
    {
    }

    public function showAssignedCourses(Request $request): View
    {
        $searchParam = $request->input('search');
        $courses = $this->courseService->getAssignedCourses($searchParam)->paginate(4);
        return view('pages.courses.assignments', compact('courses'));
    }

    public function showOwnCourses(Request $request): View
    {
        $searchParam = $request->input('search');
        $courses = $this->courseService->getOwnCourses($searchParam)->paginate(4);
        return view('pages.courses.own', compact('courses'));
    }

    public function assign(Request $request, int $courseId): RedirectResponse
    {
        if (empty($request->input('studentEmails'))) {
            if (!empty($request->input('user_id'))) {
                $this->courseService->assign($request->input('user_id'), $courseId);
            }
            return redirect()->route('courses.edit.assignments', ['id' => $courseId, 'state' => 'all']);
        } else {
            $emails = preg_split('/\n|\r\n?/', $request->input('studentEmails'));
            for ($i = 0; $i < count($emails); $i++) {
                $emails[$i] = trim($emails[$i]);
            }
            $this->courseService->assignMany($emails, $courseId);
            return redirect()->route('courses.edit.assignments', ['id' => $courseId, 'state' => 'already']);
        }
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
        $myCourseProgress = $this->statementsService->getStudentLocalProgress(auth()->id(), $courseId, count($course->content));

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
        $studentsProgress = [];

        if ($state == 'all') {
            $users = $this->courseService->getUnassignedUsers($searchParam, $courseId)->paginate(8);
        } elseif ($state == 'already') {
            $course = $this->courseService->getCourse($courseId);
            $users = $this->courseService->getAssignedUsers($searchParam, $courseId, $course)->paginate(8);
            $sectionsCourse = json_decode($course->content, true);

            foreach ($users as $user) {
                $progressStatements = $this->statementsService->getStudentLocalProgress($user->user_id, $courseId, count($sectionsCourse));
                $studentsProgress[$user->user_id] = $progressStatements['progress'];
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
        $validated['author_id'] = auth()->id();
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
        $course = $this->courseService->getCourseWithTrashed($courseId);
        $this->authorize('restore', [$course]);
        $this->courseService->restore($courseId);
        return redirect()->route('courses.own');
    }

    public function statistics(int $courseId): View
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('update', [$course]);
        $passedSectionCount = 0;

        $assignmentsCount = $this->courseService->getAssignmentsCount($courseId);

//        $userIds = $this->courseService->getAssignedUsers('', $courseId)->get()->pluck('user_id');

        $assignmentsPassed = $this->courseService->getAssignmentsPassed($courseId)->get();

        foreach ($assignmentsPassed as $item) {
            if (count($item->stats)) {
                $passedSectionCount++;
            }
        }

        $count = [
            'CourseLaunched' => 9,
            'CoursePassed' => 9,
            'CourseAssigned' => $assignmentsCount,
            'SectionPassed' => $passedSectionCount,
        ];
        return view('pages.courses.statistics', compact('count', 'courseId'));
    }
}
