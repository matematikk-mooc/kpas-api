<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class AddSurveyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'course_id' => 'required|int',
            'title' => 'string|nullable',
            'title_internal' => 'required|string',
            'questions' => 'array|nullable',
        ];
    }
}
