<?php

namespace App\Users\Requests;

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
            'name' => [
                'required',
                'max:70',
                "regex:/^(([a-zA-Z'-]{1,70})|([а-яА-ЯЁё'-]{1,70}))$/u"
            ],
            'surname' => [
                'required',
                'max:70',
                "regex:/^(([a-zA-Z'-]{1,70})|([а-яА-ЯЁё'-]{1,70}))$/u"
            ],
            'patronymic' => [
                'nullable',
                'max:70',
                "regex:/^(([a-zA-Z'-]{1,70})|([а-яА-ЯЁё'-]{1,70}))$/u"
            ],
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
        ];
    }
}
