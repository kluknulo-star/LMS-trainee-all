<?php

namespace App\Services;

use App\Users\Controllers\LoginController;
use App\Users\Models\User;
use http\Env\Response;
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

        $avatar = $user->getAvatar();
        $emailVerify = NOW();
        $rememberToken = Str::random(20);

        $randPassword = 'Pass-word12345'; //Str::random(30);
        $password = Hash::make($randPassword);
        $data = ['email' => $email, 'password' => $password, 'name' => $name, 'avatar_filename' => $avatar,
            'surname' => $surname, 'email_verified_at' => $emailVerify, 'remember_token' => $rememberToken];

        if($this->checkEmptyColumn($data)) {
            $u = User::where('email', $email)->first();
            if ($u) {
                return $u->fill(['name' => $name, 'surname' => $surname, 'avatar' => $avatar]);
            } else {
                return User::create($data);
            }
        }
        return false;
    }

    public function checkEmptyColumn($data)
    {
        foreach ($data as $column) {
            if(empty($column)) {
                return false;
            }
        }
        return true;
    }
}
