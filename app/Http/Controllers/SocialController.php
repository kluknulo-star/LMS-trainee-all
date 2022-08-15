<?php

namespace App\Http\Controllers;

use App\Services\SocialService;
use App\Users\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function index()
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    public function callBack()
    {
        $user = Socialite::driver('vkontakte')->stateless()->user();
        $objSocial = new SocialService();
        $saveUser = $objSocial->saveSocialData($user);
        if($saveUser) {
            $credentials = ['email' => $saveUser->email, 'password' => '8710oMet-rgw96Ts'];
            if (Auth::attempt($credentials)) {
                return redirect()->intended('courses');
            }
        }
        return redirect()->route('login')->withErrors(['Go to the application and provide access to your data (Required: last name, first name, mail. Optional: avatar)']);
    }
}
