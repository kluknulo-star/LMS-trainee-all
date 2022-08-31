<?php

namespace App\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Mail\EmailConfirmation;
use App\Users\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class UserEmailConfirmationController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function requestEmailConfirmation(int $id)
    {
        $user = $this->userService->getUser($id);

        if($user->isEmailConfirmed()) {
            return redirect()->route('courses.assignments');
        }

        return view('pages.mail.request-email-confirmation', ['user' => $user]);
    }

    public function sendEmailConfirmation(Request $request, int $id)
    {
        $email = $request->get('email');
        $user = $this->userService->getUser($id);
        $token = $this->userService->getEmailConfirmationToken($user);

        Mail::to($email)->send(new EmailConfirmation($user, $token));
        return redirect()->back()->with('success', 'Mail was successfully sent. Check your mailbox!');
    }

    public function emailConfirmed(int $id, string $token)
    {
        $user = $this->userService->getUser($id);

        if ($user->email_confirmation_token !== $token) {
            return redirect()->route(
                'users.request.email.confirmation',
                [
                    'id' => $user->getKey()
                ]
            );
        }

        $this->userService->update([
            'email_confirmed_at' => now(),
            'email_confirmation_token' => null,
            ], $id);

        return redirect()->route('courses.assignments');
    }
}
