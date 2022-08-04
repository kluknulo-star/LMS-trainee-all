<?php

namespace App\Users\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function login()
    {
        return view('pages/login');
    }
    public function register()
    {
        return view('pages/register');
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

    public function store(Request $request)
    {
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

    public function destroy(Request $request)
    {
    }
}
