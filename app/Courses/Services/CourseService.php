<?php

namespace App\Courses\Services;

use App\Courses\Models\Assignment;
use App\Courses\Models\Course;
use App\Courses\Repositories\CourseRepository;
use App\Http\Mail\EmailAssignNewUser;
use App\Users\Repositories\UserRepository;
use App\Users\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CourseService
{
    public function __construct(
        private CourseRepository $courseRepository,
        private UserRepository $userRepository,
        private UserService $userService,
    )
    {
    }

    public function getCourse($id): Model
    {
        return $this->courseRepository->getCourse($id);
    }

    public function getCourseWithTrashed($id): Model
    {
        return $this->courseRepository->getCourseWithTrashed($id);
    }

    public function getAssignedCourses($searchParam = ''): BelongsToMany
    {
        return $this->courseRepository->getAssignedCourses($searchParam);
    }

    public function getOwnCourses($searchParam = ''): HasMany
    {
        return $this->courseRepository->getOwnCourses($searchParam);
    }

    public function getAll()
    {
        return $this->courseRepository->getAll();
    }

    public function assign($userId, $courseId): Assignment
    {
        return $this->courseRepository->createAssign($userId, $courseId);
    }

    public function assignMany($emails, $courseId): int
    {
        $userIds = [];
        $course = $this->courseRepository->getCourse($courseId);

        foreach($emails as $email) {
            if(!($user = $this->userRepository->getUserByEmail($email))) {
                $user = $this->userService->store([
                    'name' => 'Temporary Name',
                    'surname' => 'Temporary Surname',
                    'email' => $email,
                    'password' => $password = Str::random(),
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

    public function deduct($userId, $courseId): bool
    {
        return $this->courseRepository->destroyAssignment($userId, $courseId);
    }

    public function getUnassignedUsers($searchParam, $courseId): Builder
    {
        $users = $this->courseRepository->getUnassignedUsers($courseId);
        return $users->orderByDesc('user_id')->search($searchParam);
    }

    public function getAssignedUsers($searchParam, $courseId, $course): BelongsToMany
    {
        $users = $course->assignedUsers();
        return $users->orderByDesc('user_id')->search($searchParam);
    }

    public function getAssignmentsCount($courseId)
    {
        return $this->courseRepository->getAssignments($courseId);
    }

    public function getAssignmentsPassed($courseId)
    {
        return $this->courseRepository->getAssignmentsPassed($courseId);
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
}
