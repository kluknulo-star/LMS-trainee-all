<?php

namespace App\Http\Mail;

use App\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailResetPassword extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public User $user, public string $token)
    {
    }

    public function build()
    {
        return $this->view('pages.mail.reset-password');
    }
}
