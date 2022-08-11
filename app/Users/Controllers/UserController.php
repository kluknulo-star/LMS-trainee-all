<?php

namespace App\Users\Controllers;

use App\Courses\Models\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\AvararUpdateRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Users\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function __construct(private UserService $service)
    {

    }

    public function index(Request $request)
    {
        $searchParam = $request->input('search');
        $users = $this->service->index($searchParam);
        return view('pages.users.users', compact('users'));
    }

    public function show(int $id)
    {
        $assignedCourses = $this->service->getAssignedUserCourses($id);
        $ownCourses = $this->service->getOwnUserCourses($id);
        $user = $this->service->getUser($id);
        return view('pages.users.profile', compact('user', 'ownCourses', 'assignedCourses'));
    }

    public function create()
    {
        return view('pages.users.create');
    }

    public function store(CreateUserRequest $request)
    {
        $validated = $request->validated();
        $this->service->create($validated);
        return redirect()->route('users');
    }

    public function edit(int $id)
    {
        $user = $this->service->getUser($id);
        return view('pages.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $validated = $request->validated();
        $this->service->update($validated, $id);
        $user = $this->service->getUser($id);
        return redirect()->route('users.show', ['id' => $user->user_id]);
    }

    public function editAvatar(int $id)
    {
        $user = $this->service->getUser($id);
        return view('pages.users.edit_avatar', compact('user'));
    }

    public function updateAvatar(AvararUpdateRequest $request)
    {
        if (empty($request)) {
            return redirect()->route('users.show', ['id' => auth()->id()]);
        }
        $avatar = $request->file('avatar');
        $this->service->updateAvatar($avatar);
        return redirect()->route('users.show', ['id' => auth()->id()]);
    }

    public function destroy(int $id)
    {
        $this->service->destroy($id);
        return redirect()->route('users');
    }

    public function restore(int $id)
    {
        $this->service->restore($id);
        return redirect()->route('users');
    }
}
