<?php

namespace App\Http\Requests\Enrollment;

use Illuminate\Foundation\Http\FormRequest;

class EnrollUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'role' => 'required|string',
        ];
    }
}
