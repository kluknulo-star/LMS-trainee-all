<?php

namespace App\Courses\Services;

use App\Courses\Models\AssignableCourse;
use App\Courses\Models\Course;
use App\Users\Models\User;

class CourseService
{
    public function getCourse($id)
    {
        return Course::findOrFail($id);
    }
    public function getAssignments($searchParam)
    {
        return auth()->user()
                     ->assignedCourses()
                     ->withCount('assignedUsers')
                     ->orderByDesc('course_id')
                     ->search($searchParam);
    }

    public function getOwn($searchParam)
    {
        return auth()->user()
                     ->courses()
                     ->withTrashed()
                     ->withCount('assignedUsers')
                     ->orderByDesc('course_id')
                     ->search($searchParam);
    }

    public function assign($userId, $courseId, $action)
    {
        switch ($action) {
            case 'assign':
                return AssignableCourse::create([
                    'student_id' => $userId,
                    'course_id' => $courseId,
                ]);
            case 'deduct':
                return AssignableCourse::where([
                    ['course_id', '=', $courseId],
                    ['student_id', '=', $userId],
                ])->delete();
        }
    }

    public function editAssignments($state, $searchParam, $courseId)
    {
        $users = Course::findOrFail($courseId)->assignedUsers();

        if ($state != 'already') {
            $users = User::whereNotIn('user_id', $users->pluck('user_id')->toArray());
        }

        return $users->orderByDesc('user_id')
                     ->search($searchParam);
    }

    public function update($id, $validated)
    {
        $course = $this->getCourse($id);
        return $course->update($validated);
    }

    public function store($validated)
    {
        $validated['author_id'] = auth()->id();
        return Course::create($validated);
    }

    public function destroy($id)
    {
        return Course::where([
            ['course_id', '=', $id],
            ['author_id', '=', auth()->id()],
        ])->delete();
    }

    public function restore($id)
    {
        return Course::where([
            ['course_id', '=', $id],
            ['author_id', '=', auth()->id()],
        ])->restore();
    }
}
