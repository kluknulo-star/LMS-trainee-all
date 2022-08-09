<?php

namespace App\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $searchParam = $request->input('search');
        $recordsPerPage = 8;

        $users = User::withTrashed()->orderBy('user_id', 'desc')
            ->search($searchParam)
            ->paginate($recordsPerPage);
        return view('pages.users.users', compact('users'));
    }

    public function show(int $id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.profile', compact('user'));
    }

    public function create()
    {
        return view('pages.users.create');
    }

    public function store(CreateUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        unset($validated['password_confirmation']);

        User::create($validated);
        return redirect()->route('users');
    }

    public function edit(int $id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $validated = $request->validated();

        if (is_null($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user = User::findOrFail($id);
        $user->update($validated);
        return redirect()->route('users.show', ['id' => $user->user_id]);
    }

    public function destroy(int $id)
    {
        if (Auth::id() != $id)
        {
            User::where('user_id', $id)->delete();
        }
        return redirect()->route('users');
    }

    public function restore(int $id)
    {
        User::withTrashed()->where('user_id', $id)->restore();
        return redirect()->route('users');
    }

}
