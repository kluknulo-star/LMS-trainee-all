<?php

namespace App\Courses\Controllers;

use App\Courses\Models\Assignment;
use App\Courses\Repositories\CourseRepository;
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
    public function __construct(
        private CourseService $courseService,
        private StatementService $statementsService,
        private CourseRepository $courseRepository,
    )
    {
    }

    public function showAssignedCourses(Request $request): View
    {
        $searchParam = $request->input('search');
        $courses = $this->courseRepository->getAssignedCourses($searchParam)->paginate(4);
        return view('pages.courses.assignments', compact('courses'));
    }

    public function showOwnCourses(Request $request): View
    {
        $searchParam = $request->input('search');
        $courses = $this->courseRepository->getOwnCourses($searchParam)->paginate(4);
        return view('pages.courses.own', compact('courses'));
    }

    public function assign(Request $request, int $courseId): RedirectResponse
    {
        if (empty($request->input('studentEmails'))) {
            if (!empty($request->input('user_id'))) {
                $this->courseRepository->createAssign($request->input('user_id'), $courseId);
            }
            return redirect()->route('courses.edit.assignments', ['id' => $courseId, 'state' => 'all'])
                             ->with(['success' => __('success.'.__FUNCTION__.'Course')]);
        }

        $emails = preg_split('/\n|\r\n?/', $request->input('studentEmails'));
        $this->courseService->assignMany($courseId, $emails);
        return redirect()->route('courses.edit.assignments', ['id' => $courseId, 'state' => 'already'])
                         ->with(['success' => __('success.'.__FUNCTION__.'Course')]);
    }

    public function deduct(Request $request, int $courseId): RedirectResponse
    {
        $userId = $request->input('user_id');
        $this->courseRepository->destroyAssignment($userId, $courseId);
        return redirect()->route('courses.edit.assignments', ['id' => $courseId, 'state' => 'already'])
                         ->with(['success' => __('success.'.__FUNCTION__.'Course')]);
    }

    public function play(int $courseId): View
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('view', [$course]);
        $course->content = $course->content->where('deleted_at', NULL);
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
        $users = $this->courseService->getAssignmentsUsersByState($state, $courseId, $searchParam);
        $studentsProgress = $this->courseService->getAssignmentsUsersProgress($state, $users, $courseId);
        return view('pages.courses.assign', compact('users', 'courseId', 'state', 'studentsProgress'));
    }

    public function update(UpdateCourseRequest $request, int $courseId): RedirectResponse
    {
        $validated = $request->validated();
        $this->courseRepository->getCourse($courseId)->update($validated);
        return redirect()->route('courses.edit', ['id' => $courseId])
                         ->with(['success' => __('success.'.__FUNCTION__.'Course')]);
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
        $this->courseRepository->store($validated);
        return redirect()->route('courses.own')
                         ->with(['success' => __('success.'.__FUNCTION__.'Course')]);
    }

    public function destroy(int $courseId): RedirectResponse
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('delete', [$course]);
        $this->courseRepository->destroy($courseId);
        return redirect()->route('courses.own')
                         ->with(['success' => __('success.'.__FUNCTION__.'Course')]);
    }

    public function restore(int $courseId): RedirectResponse
    {
        $course = $this->courseRepository->getCourseWithTrashed($courseId);
        $this->authorize('restore', [$course]);
        $this->courseRepository->restore($courseId);
        return redirect()->route('courses.own')
                         ->with(['success' => __('success.'.__FUNCTION__.'Course')]);
    }

    public function statistics(int $courseId): View
    {
        $course = $this->courseService->getCourse($courseId);
        $this->authorize('update', [$course]);
        $coursePassedCount = $this->statementsService->getCoursesStatements($courseId);

        $count = [
            'CourseLaunched' => count($coursePassedCount['launched']),
            'CoursePassed' => count($coursePassedCount['passed']),
            'CourseAssigned' => $this->courseRepository->getAssignments($courseId),
            'SectionPassed' => $this->courseService->getPassedSectionsCount($courseId),
        ];
        return view('pages.courses.statistics', compact('count', 'courseId'));
    }
}
