<?php

namespace App\Http\Mail;

use App\Courses\Models\Course;
use App\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailAssignNewUser extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public User $user, public string $password, public Course $course)
    {
    }

    public function build()
    {
        return $this->view('pages.mail.email-assign-new-user');
    }
}
