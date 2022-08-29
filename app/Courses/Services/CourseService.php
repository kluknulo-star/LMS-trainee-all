<?php

namespace App\Courses\Services;

use App\Courses\Models\Assignment;
use App\Courses\Models\Course;
use App\Courses\Repositories\CourseRepository;
use App\Users\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CourseService
{
    public function __construct(
        private CourseRepository $courseRepository,
        private UserRepository $userRepository,
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
        $userIds = $this->userRepository->getUserIdsByEmails($emails);
        $assignData = [];
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
