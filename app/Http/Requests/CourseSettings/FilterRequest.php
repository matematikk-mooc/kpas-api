<?php

namespace App\Http\Requests\CourseSettings;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filter_name' => 'required|string',
            'type' => ['required', Rule::in(['CATEGORY', 'TARGET', 0, 1])],
        ];
    }
}
