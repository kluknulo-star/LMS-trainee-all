<?php

namespace App\Courses\Repositories;

use App\Courses\Models\Assignment;
use App\Courses\Models\Course;
use App\Courses\Models\ItemsStats;
use App\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseRepository
{
    public function getCourse($id): Model
    {
        return Course::with('content.type')->with('author')->find($id);
    }

    public function getCourseWithTrashed($id)
    {
        return Course::withTrashed()->with('content.type')->with('author')->find($id);
    }

    public function getAssignments($id)
    {
        return Assignment::where('course_id', $id)->count();
    }

    public function getAssignmentsPassed($courseId)
    {
        return Assignment::where('course_id', $courseId)->with('stats');
    }

    public function getAssignedCourses($searchParam): BelongsToMany
    {
        return auth()->user()
                     ->assignedCourses()
                     ->with('author')
                     ->withCount('assignedUsers')
                     ->orderByDesc('course_id')
                     ->search($searchParam);
    }

    public function getOwnCourses($searchParam): HasMany
    {
        return auth()->user()
                     ->courses()
                     ->withTrashed()
                     ->withCount('assignedUsers')
                     ->orderByDesc('course_id')
                     ->search($searchParam);
    }

    public function getAll()
    {
        return Course::withCount('assignedUsers')->get();
    }

    public function createAssign($userId, $courseId): Assignment
    {
        return Assignment::firstOrCreate([
            'student_id' => $userId,
            'course_id' => $courseId,
        ]);
    }

    public function createManyAssignments($assignData): int
    {
        $createCount = 0;
        for ($i = 0; $i < count($assignData); $i++) {
            if (Assignment::firstOrCreate($assignData[$i])) {
                $createCount++;
            }
        }
        return $createCount;
    }

    public function destroyAssignment($userId, $courseId): bool
    {
        return Assignment::query()->where([
            ['course_id', '=', $courseId],
            ['student_id', '=', $userId],
        ])->delete();
    }

    public function getUnassignedUsers($courseId): Builder
    {
        return User::whereDoesntHave('assignments', function(Builder $query) use ($courseId) {
            $query->where('course_id', '=', $courseId);
        });
    }

    public function store($validated): Course
    {
        return Course::create($validated);
    }

    public function destroy($courseId): bool
    {
        return Course::where([['course_id', '=', $courseId]])->delete();
    }

    public function restore($courseId): bool
    {
        return Course::where([['course_id', '=', $courseId]])->restore();
    }
}
