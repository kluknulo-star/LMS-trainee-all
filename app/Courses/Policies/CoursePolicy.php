<?php

namespace App\Courses\Policies;

use App\Users;
use App\Courses;
use App\Users\Models\User;
use App\Courses\Models\Course;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Users\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Users\Models\\User  $user
     * @param  \App\Courses\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Course $course): bool
    {
        return $user->assignedCourses()
                    ->where('courses.course_id', $course->course_id)
                    ->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Users\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): bool
    {
        return $user->is_teacher;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Users\Models\User  $user
     * @param  \App\Courses\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Course $course): bool
    {
        return $user->user_id == $course->author_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Users\Models\User  $user
     * @param  \App\Courses\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Course $course): bool
    {
        return $user->user_id == $course->author_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Users\Models\User  $user
     * @param  \App\Courses\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Course $course): bool
    {
        return $user->user_id == $course->author_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Users\Models\User  $user
     * @param  \App\Courses\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Course $course): bool
    {
        return $user->user_id == $course->author_id;
    }
}
