<?php

namespace App\Rules;

use App\Users\Models\OldUserPassword;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Hash;

class LastPasswordsCase implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $oldPasswords = OldUserPassword::where('user_id', auth()->id())
            ->get()->sortByDesc('old_password_id')->take(10)->pluck('old_password')->ToArray();
        $flagInArray = false;
        foreach ($oldPasswords as $oldPassword) {
            if (password_verify($value, $oldPassword)) {
                $flagInArray = true;
            }
        }
        if($flagInArray === true){
            $fail('The password must not match the last 10 passwords');
        }
    }
}
