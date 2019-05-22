<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class GetCategoriesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'course_id' => 'required|number',
        ];
    }
}
