<?php

namespace App\Users\Policies;

use App\Users;
use App\Users\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeacherPolicy
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
        return $user->is_teacher;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Users\Models\User  $user
     * @param  \App\Users\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model): bool
    {
        return $user->is_teacher || $model->user_id === $user->user_id;
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
     * @param  \App\Users\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model): bool
    {
        return !$model->is_teacher || $user->user_id === $model->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Users\Models\User  $user
     * @param  \App\Users\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model): bool
    {
        return !$model->is_teacher;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Users\Models\User  $user
     * @param  \App\Users\Models\User $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model): bool
    {
        return $model->is_teacher;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Users\Models\User  $user
     * @param  \App\Users\Models\User $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $model->is_teacher;
    }
}
