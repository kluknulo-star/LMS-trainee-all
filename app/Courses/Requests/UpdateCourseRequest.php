<?php

namespace App\Courses\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
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
            'title' => [
                'required',
                Rule::unique('courses')->ignore($this->route('id'), 'course_id'),
                'regex:~^([^<>]*)$~',
                'between:5,90',
                ],
            'description' => 'nullable|regex:~^([^<>]*)$~|max:255',
        ];
    }
}
