<?php

namespace App\Users\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        return view('users');
    }

    public function show(Request $request)
    {
        return view();
    }

    public function edit(Request $request)
    {
        return view();
    }

    public function update(Request $request)
    {
        return view();
    }

    public function destroy(Request $request)
    {

    }

    public function login(Request $request)
    {
        return view('login');
    }

    public function register(Request $request)
    {
        return view('register');
    }

}
