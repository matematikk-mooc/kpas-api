<?php

namespace App\Http\Requests\CourseSettings;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CourseSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'unmaintained_since' => 'string|nullable',
            'role_support' => 'required|boolean',
            'licence' => 'required|boolean',
            'banner_type' => ['required', Rule::in(['ALERT', 'NOTIFICATION', 'FEEDBACK', 'UNMAINTAINED','NONE', 0, 1, 2, 3, 4])],
            'banner_text' => 'string|nullable',
            'multilang' => ['required', Rule::in(['ALL', 'SE', 'NN', 'NONE', 0, 1, 2, 3])],
            'courseFilters' => 'array|nullable',
            'courseCategory' => 'array|nullable',
        ];
    }
}
