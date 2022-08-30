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
