<?php

namespace App\Users\Services;

use App\Courses\Models\Course;
use App\Users\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserService
{
    public function index($searchParam)
    {
        return User::withTrashed()->orderBy('user_id', 'desc')->search($searchParam);
    }

    public function getUser($id)
    {
        return User::findOrFail($id);
    }

    public function getAssignedUserCourses(User $user)
    {
        return $user->assignedCourses()->orderByDesc('course_id');
    }

    public function getOwnUserCourses(User $user)
    {
        return $user->courses()->orderByDesc('course_id');
    }

    public function create($validated)
    {
        $validated['password'] = Hash::make($validated['password']);
        unset($validated['password_confirmation']);
        return User::create($validated);
    }

    public function update($validated, $id)
    {
        if (is_null($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        $user = $this->getUser($id);
        return $user->update($validated);
    }

    public function destroy($id)
    {
        if (auth()->id() != $id) {
            return optional(User::where('user_id', $id))->delete();
        }
    }

    public function restore($id)
    {
        return optional(User::withTrashed()->where('user_id', $id))->restore();
    }

    public function updateAvatar($avatar)
    {
        auth()->user()->clearAvatars(auth()->id());
        $filename = time() . '.' . $avatar->getClientOriginalExtension();
        Image::make($avatar)->resize(300, 300)
            ->save( public_path(auth()->user()->getAvatarsPath(auth()->id()) . $filename ) );

        auth()->user()->avatar_filename = $filename;
        auth()->user()->save();
    }
}
