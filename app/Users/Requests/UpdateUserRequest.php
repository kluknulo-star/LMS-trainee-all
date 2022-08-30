<?php

namespace App\Users\Requests;

use App\Rules\LastPasswordsCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'user_id' => [
                'exists:users,user_id'
            ],
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
            'email' => [
                'required',
                Rule::unique('users')->ignore($this->route('id'), 'user_id'),
                'email:rfc,dns',
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->symbols()
                    ->uncompromised()
                    ->numbers()
                    ->mixedCase(),
                new LastPasswordsCase,
            ],
        ];
    }
}
