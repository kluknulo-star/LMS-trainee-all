<?php

namespace App\Users\Controllers;

use App\Courses\Services\ExportCourseService;
use App\Http\Controllers\Controller;
use App\Users\Requests\AvatarUpdateRequest;
use App\Users\Requests\CreateUserRequest;
use App\Users\Requests\UpdateUserRequest;
use App\Users\Services\UpdateAvatarService;
use App\Users\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    public function __construct(
        private UserService $service,
        private ExportCourseService $exportCourseService,
    )
    {

    }

    public function index(Request $request): View
    {
        $this->authorize('view', [auth()->user()]);
        $searchParam = $request->input('search');
        $users = $this->service->index($searchParam)->paginate(8);
        return view('pages.users.users', compact('users'));
    }

    public function show(int $id): View
    {
        $user = $this->service->getUser($id);
        $exports = $this->exportCourseService->getExports($id);
        $this->authorize('view', [$user, auth()->user()]);
        return view('pages.users.profile', compact('user', 'exports'));
    }

    public function create(): View
    {
        $this->authorize('create', [auth()->user()]);
        return view('pages.users.create');
    }

    public function store(CreateUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->service->store($validated);
        return redirect()->route('users');
    }

    public function edit(int $id): View
    {
        $user = $this->service->getUser($id);
        $this->authorize('update', [$user]);
        return view('pages.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        $user = $this->service->getUser($id);
        $this->authorize('update', [$user]);
        $validated = $request->validated();
        $user = $this->service->update($validated, $id);
        return redirect()->route('users.show', ['id' => $user->user_id]);
    }

    public function editAvatar(int $id): View
    {
        $user = $this->service->getUser($id);
        $this->authorize('update', [$user]);
        return view('pages.users.edit_avatar', compact('user'));
    }

    public function updateAvatar(AvatarUpdateRequest $request): RedirectResponse
    {
        if (empty($request)) {
            return redirect()->route('users.show', ['id' => auth()->id()]);
        }
        $avatar = $request->file('avatar');
        $updateAvatarService = new UpdateAvatarService();
        $updateAvatarService->updateAvatar($avatar);
        return redirect()->route('users.show', ['id' => auth()->id()]);
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = $this->service->getUser($id);
        $this->authorize('delete', [$user]);
        $this->service->destroy($id);
        return redirect()->route('users');
    }

    public function restore(int $id): RedirectResponse
    {
        $this->authorize('restore', [auth()->user()]);
        $this->service->restore($id);
        return redirect()->route('users');
    }

    public function assignTeacher(int $id): RedirectResponse
    {
        $this->service->assignTeacher($id);
        return redirect()->route('users.show', ['id' => $id]);
    }
}
