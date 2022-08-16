<?php

namespace App\Users\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests\AvararUpdateRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Users\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    public function __construct(private UserService $service)
    {

    }

    public function index(Request $request)
    {
        $this->authorize('view', [auth()->user()]);
        $searchParam = $request->input('search');
        $users = $this->service->index($searchParam)->paginate(8);
        return view('pages.users.users', compact('users'));
    }

    public function show(int $id)
    {
        $user = $this->service->getUser($id);
        $this->authorize('view', [$user, auth()->user()]);
        $ownCourses = $this->service->getOwnUserCourses($user)->paginate(4);
        $assignedCourses = $this->service->getAssignedUserCourses($user)->paginate(4);
        return view('pages.users.profile', compact('user', 'ownCourses', 'assignedCourses'));
    }

    public function create()
    {
        $this->authorize('create', [auth()->user()]);
        return view('pages.users.create');
    }

    public function store(CreateUserRequest $request): RedirectResponse
    {
        $this->authorize('create', [auth()->user()]);
        $validated = $request->validated();
        $this->service->create($validated);
        return redirect()->route('users');
    }

    public function edit(int $id): View
    {
        $user = $this->service->getUser($id);
        $this->authorize('update', [$user]);
        return view('pages.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $user = $this->service->getUser($id);
        $this->authorize('update', [$user]);
        $validated = $request->validated();
        $this->service->update($validated, $id);
        $user = $this->service->getUser($id);
        return redirect()->route('users.show', ['id' => $user->user_id]);
    }

    public function editAvatar(int $id)
    {
        $user = $this->service->getUser($id);
        $this->authorize('update', [$user]);
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
        $user = $this->service->getUser($id);
        $this->authorize('delete', [$user]);
        $this->service->destroy($id);
        return redirect()->route('users');
    }

    public function restore(int $id)
    {
        $this->authorize('restore', [auth()->user()]);
        $this->service->restore($id);
        return redirect()->route('users');
    }
}
