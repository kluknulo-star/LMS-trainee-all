<?php

namespace App\Courses\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCourseContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
//        $sectionType = ['Photo', 'Article', 'Link'];
        return [
            'type' => 'required',
            'sectionTitle' => 'required'
        ];
    }
}
