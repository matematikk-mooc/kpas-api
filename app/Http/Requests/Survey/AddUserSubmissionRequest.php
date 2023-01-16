<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class AddUserSubmissionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'answers' => 'array|required',
            'answers.*.question_id' => 'integer|required|gt:0|lt:9999999999999999',
            'answers.*.value' => 'string|required|min:1|max:2000',
        ];
    }
}
