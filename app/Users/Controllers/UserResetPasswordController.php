<?php

namespace App\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Mail\EmailResetPassword;
use App\Users\Requests\ResetUserPasswordRequest;
use App\Users\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserResetPasswordController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function requestMailForResettingPassword()
    {
        return view('pages.mail.request-mail-for-resetting-password');
    }

    public function sendResetPasswordMail(Request $request)
    {
        $email = $request->get('email');
        $user = $this->userService->getUserByEmail($email);
        $token = $this->userService->getResetPasswordToken($user);

        if(!is_null($user))
        {
            Mail::to($email)->send(new EmailResetPassword($user, $token));
            return redirect()->back()->with('success', 'Mail was successfully sent. Check your mailbox!');
        }

        return redirect()->back()->with('fail', 'User with such email doesn\'t exist');
    }

    public function showResetPasswordForm(int $id, string $token)
    {
        $user = $this->userService->getUser($id);

        if($user->reset_password_token !== $token)
        {
            return redirect()->route('request.password.reset');
        }

        return view('pages.users.reset-password-form', compact('user', 'token'));
    }

    public function storeNewPassword(ResetUserPasswordRequest $request, int $id)
    {
        $validated = $request->validated();
        $this->userService->update($validated, $id);
        $this->userService->update([
            'reset_password_token' => null,
        ], $id);

        return redirect()->route('login');
    }
}
