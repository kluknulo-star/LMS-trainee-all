<?php

namespace App\Courses\Services;

use App\Courses\Helpers\ClientLRS;
use App\Courses\Models\AssignableCourse;
use App\Courses\Models\Course;
use App\Users\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CourseService
{
    public function getCourse($id, $decodeContent = false): Course
    {
        $course = Course::findOrFail($id);

        if ($decodeContent) {
            $course->content = json_decode($course->content, true);
        }

        return $course;
    }

    public function getAssignments($searchParam = ''): BelongsToMany
    {
        return auth()->user()
                     ->assignedCourses()
                     ->withCount('assignedUsers')
                     ->orderByDesc('course_id')
                     ->search($searchParam);
    }

    public function getOwn($searchParam = ''): HasMany
    {
        return auth()->user()
                     ->courses()
                     ->withTrashed()
                     ->withCount('assignedUsers')
                     ->orderByDesc('course_id')
                     ->search($searchParam);
    }

    public function getAll(): Collection
    {
        return Course::withCount('assignedUsers')->get();
    }

    public function assign($userId, $courseId): AssignableCourse
    {
        return AssignableCourse::create([
            'student_id' => $userId,
            'course_id' => $courseId,
        ]);
    }

    public function deduct($userId, $courseId): bool
    {
        return AssignableCourse::where([
            ['course_id', '=', $courseId],
            ['student_id', '=', $userId],
        ])->delete();
    }

    public function getUnassignedUsers($searchParam, $courseId) // Builder ?
    {
        $users = $this->getCourse($courseId)->assignedUsers();
        $users = User::whereNotIn('user_id', $users->pluck('user_id')->toArray());

        return $users->orderByDesc('user_id')
                     ->search($searchParam);
    }

    public function getAssignedUsers($searchParam, $courseId): BelongsToMany
    {
        $users = $this->getCourse($courseId)->assignedUsers();

        return $users->orderByDesc('user_id')
                     ->search($searchParam);
    }

    public function update($courseId, $validated): bool
    {
        $course = $this->getCourse($courseId);
        return $course->update($validated);
    }

    public function store($validated): Course
    {
        $validated['author_id'] = auth()->id();
        return Course::create($validated);
    }

    public function destroy($courseId): bool
    {
        return Course::where([
            ['course_id', '=', $courseId],
            ['author_id', '=', auth()->id()],
        ])->delete();
    }

    public function restore($courseId): bool
    {
        return Course::where([
            ['course_id', '=', $courseId],
            ['author_id', '=', auth()->id()],
        ])->restore();
    }

    public function sendLaunchStatement(Course $course)
    {
        /** @var User $user */
        $user = auth()->user();
        $verb = 'launched';
        $statement = ClientLRS::compileStatement($user, $verb, $course);
        return ClientLRS::sendStatement($statement);
    }

    public function getStudentProgress(int $courseId, string $email) : array
    {
        return ClientLRS::getProgressStudent($email, $courseId);
    }

}
