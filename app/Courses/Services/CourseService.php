<?php

namespace App\Courses\Services;

use App\Courses\Models\Course;
use App\Courses\Repositories\CourseRepository;
use App\Http\Mail\EmailAssignNewUser;
use App\Users\Repositories\UserRepository;
use App\Users\Services\UserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Courses\Services\StatementService;

class CourseService
{
    public function __construct(
        private CourseRepository $courseRepository,
        private UserRepository $userRepository,
        private UserService $userService,
        private StatementService $statementService,
    )
    {
    }

    public function getCourse($id): Model
    {
        return $this->courseRepository->getCourse($id);
    }

    public function assignMany($courseId, $emails): int
    {
        for ($i = 0; $i < count($emails); $i++) {
            $emails[$i] = trim($emails[$i]);
        }

        $userIds = [];
        $course = $this->courseRepository->getCourse($courseId);

        foreach($emails as $email) {
            if(!($user = $this->userRepository->getUserByEmail($email))) {
                $user = $this->userService->store([
                    'name' => 'Temporary Name',
                    'surname' => 'Temporary Surname',
                    'email' => $email,
                    'password' => $password = Str::random(),
                    'is_teacher' => 0,
                    'email_confirmed_at' => now()
                ]);
                Mail::to($email)->send(new EmailAssignNewUser($user, $password, $course));
            }

            $userIds[] = $user->getKey();
        }

        foreach ($userIds as $id) {
            $assignData[] = ['student_id' => $id, 'course_id' => $courseId];
        }

        return $this->courseRepository->createManyAssignments($assignData);
    }

    public function update($courseId, $validated): bool
    {
        $course = $this->courseRepository->getCourse($courseId);
        return $course->update($validated);
    }

    public function store($validated): Course
    {
        return $this->courseRepository->store($validated);
    }

    public function destroy($courseId): bool
    {
        return $this->courseRepository->destroy($courseId);
    }

    public function restore($courseId): bool
    {
        return $this->courseRepository->restore($courseId);
    }

    public function getAssignmentsUsersByState(string $state, int $courseId, $searchParam = '')
    {
        if ($state == 'all') {
            return $this->courseRepository->getUnassignedUsers($courseId)
                ->orderByDesc('user_id')
                ->search($searchParam)
                ->paginate(8);
        } elseif ($state == 'already') {
            return $this->getCourse($courseId)->assignedUsers()->orderByDesc('user_id')->search($searchParam)->paginate(8);
        }
    }

    public function getAssignmentsUsersProgress(string $state, $users, int $courseId)
    {
        $studentsProgress = [];
        if ($state == 'already') {
            $sectionsCourse = json_decode($this->getCourse($courseId)->content, true);
            foreach ($users as $user) {
                $progressStatements = $this->statementService->getStudentLocalProgress($user->user_id, $courseId, count($sectionsCourse));
                $studentsProgress[$user->user_id] = $progressStatements['progress'];
            }
        }
        return $studentsProgress;
    }

    public function getPassedSectionsCount(int $courseId): int
    {
        $assignmentsPassed = $this->courseRepository->getAssignmentsPassed($courseId)->get();

        $passedSectionCount = 0;
        foreach ($assignmentsPassed as $item) {
            if (count($item->stats)) {
                $passedSectionCount++;
            }
        }

        return $passedSectionCount;
    }
}
