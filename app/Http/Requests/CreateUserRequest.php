<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateUserRequest extends FormRequest
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
            'name' => 'required|alpha|max:70',
            'surname' => 'required|alpha|max:70',
            'patronymic' => 'nullable|alpha|max:70',
            'email' => 'required|unique:users,email|email:rfc,dns',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                ->letters()
                ->symbols()
                ->uncompromised()
                ->numbers()
                ->mixedCase(),
            ],
            'password_confirmation' => 'required',
        ];
    }
}
