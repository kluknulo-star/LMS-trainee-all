<?php

namespace App\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class ResetUserPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'password' => [
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->symbols()
                    ->uncompromised()
                    ->numbers()
                    ->mixedCase(),
            ],
        ];
    }
}
