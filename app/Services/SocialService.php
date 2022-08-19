<?php

namespace App\Services;

use App\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialService
{
    public function saveSocialData($user)
    {
        $email = $user->getEmail();
        $fullname = $user->getName();
        $partOfName = explode(" ", $fullname);
        $surname = $partOfName[1];
        $name = $partOfName[0];

        if($this->checkEmptyColumn($email, $surname, $name)) {
            $u = User::where('email', $email)->first();
            if ($u) {
                return $u;
            } else {
                $avatar = $user->getAvatar();
                $password = Hash::make(Str::random(60));
                $data = ['email' => $email, 'password' => $password, 'name' => $name,
                    'avatar_filename' => $avatar, 'surname' => $surname, 'email_verified_at' => NOW(),
                    'remember_token' => Str::random(20)];

                return User::create($data);
            }
        }
        return false;
    }

    public function checkEmptyColumn($email, $surname, $name)
    {
        if(empty($email) || empty($surname) || empty($name)) {
            return false;
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }
}
