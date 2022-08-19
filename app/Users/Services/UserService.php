<?php

namespace App\Users\Services;

use App\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class UserService
{
    public function index($searchParam): Builder
    {
        return User::withTrashed()->orderBy('user_id', 'desc')->search($searchParam);
    }

    public function getUser($id): User
    {
        return User::findOrFail($id);
    }

    public function create($validated): User
    {
        $validated['password'] = Hash::make($validated['password']);
        return User::create($validated);
    }

    public function update($validated, $id): User
    {
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        $user = $this->getUser($id);
        $user->update($validated);
        return $user;
    }

    public function destroy($id): bool
    {
        if (auth()->id() != $id) {
            return optional(User::where('user_id', $id))->delete();
        } else {
            return false;
        }
    }

    public function restore($id): bool
    {
        return optional(User::withTrashed()->where('user_id', $id))->restore();
    }
}
