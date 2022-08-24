<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{
    public function changeLanguage()
    {
        if (Session::get('lang') === NULL) {
            Session::put('lang', 'ru');
        }

        $locale = Session::get('lang');

        if ($locale == 'ru') {
            $locale = 'en';
        } elseif ($locale == 'en') {
            $locale = 'ru';
        }

        Session::put('lang', $locale);

        return back();
    }
}
