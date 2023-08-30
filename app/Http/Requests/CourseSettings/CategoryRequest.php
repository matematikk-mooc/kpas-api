<?php

namespace App\Http\Requests\CourseSettings;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'position' => 'integer|nullable',
            'color_code' => 'string|nullable',
        ];
    }
}
