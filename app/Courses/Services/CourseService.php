<?php

namespace App\Courses\Services;

use App\Courses\Models\AssignableCourse;
use App\Courses\Models\Course;
use App\Users\Models\User;

class CourseService
{
    public function getCourse($id, $decodeContent=false)
    {
        $course = Course::findOrFail($id);

        if ($decodeContent) {
            $course->content = json_decode($course->content, true);
        }
        return $course;
    }

    public function checkOwn($courseId)
    {
        return auth()->user()
                     ->courses()
                     ->withTrashed()
                     ->where('course_id', $courseId)
                     ->get()
                     ->isNotEmpty();
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
        if (!$this->checkOwn($courseId)) {
            return abort(403);
        }
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
        if (!$this->checkOwn($courseId)) {
            return abort(403);
        }
        $users = Course::findOrFail($courseId)->assignedUsers();

        if ($state != 'already') {
            $users = User::whereNotIn('user_id', $users->pluck('user_id')->toArray());
        }

        return $users->orderByDesc('user_id')
                     ->search($searchParam);
    }

    public function update($courseId, $validated)
    {
        if (!$this->checkOwn($courseId)) {
            return abort(403);
        }
        $course = $this->getCourse($courseId);
        return $course->update($validated);
    }

    public function store($validated)
    {
        $validated['author_id'] = auth()->id();
        return Course::create($validated);
    }

    public function destroy($courseId)
    {
        if (!$this->checkOwn($courseId)) {
            return abort(403);
        }
        return Course::where([
            ['course_id', '=', $courseId],
            ['author_id', '=', auth()->id()],
        ])->delete();
    }

    public function restore($courseId)
    {
        if (!$this->checkOwn($courseId)) {
            return abort(403);
        }
        return Course::where([
            ['course_id', '=', $courseId],
            ['author_id', '=', auth()->id()],
        ])->restore();
    }

    public function edit($courseId)
    {
        if (!$this->checkOwn($courseId)) {
            return abort(403);
        }
    }
}
