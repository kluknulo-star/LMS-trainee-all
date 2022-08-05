<?php

namespace App\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $recordsPerPage = 8;
        $users = User::orderBy('user_id', 'desc')->paginate($recordsPerPage);
        return view('pages/users/users', compact('users'));
    }

    public function show(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        return view('pages/users/profile', compact('user'));
    }

    public function create(Request $request)
    {
        return view('pages/users/create');
    }

    public function store(CreateUserRequest $request)
    {
        $validated = $request->validated();
        $userData = [
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'patronymic' => $validated['patronymic'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ];

        $user = User::create($userData);
        return redirect()->action([UserController::class, 'index']);
    }

    public function edit(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        return view('pages/users/edit', compact('user'));
    }

    public function update(Request $request)
    {
        return view();
    }

    public function destroy(Request $request, int $id)
    {
        $user = User::findorFail($id);
        $user->delete();
    }

}
