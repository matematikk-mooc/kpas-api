<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class AddSurveyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'title_internal' => 'required|string',
            'question1' => 'string|nullable',
            'question2' => 'string|nullable',
            'question3' => 'string|nullable',
        ];
    }
}
