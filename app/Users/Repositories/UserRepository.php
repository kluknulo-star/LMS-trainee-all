<?php

namespace App\Users\Repositories;

use App\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Support\Collection;

class UserRepository
{
    public function getAll($searchParam): Builder
    {
        return User::withTrashed()->orderBy('user_id', 'desc')->search($searchParam);
    }

    public function getTeachers(): Builder
    {
        return User::withTrashed()->where('is_teacher', 1)->orderBy('user_id', 'desc');
    }

    public function getUserById(int $id): User
    {
        return User::findOrFail($id);
    }

    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function store(array $validated): User
    {
        return User::create($validated);
    }

    public function destroy(int $id): bool
    {
        return User::where('user_id', $id)->delete();
    }

    public function restore(int $id): bool
    {
        return User::withTrashed()->where('user_id', $id)->restore();
    }

    public function getUserIdsByEmails(array $emails): Collection
    {
        return User::query()->whereIn('email', $emails)->get()->pluck('user_id');
    }
}
