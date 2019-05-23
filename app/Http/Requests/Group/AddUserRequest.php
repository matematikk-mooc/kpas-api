<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'group' => 'required|array',
            'group.name' => 'required|string',
            'group.description' => 'required|string',
            'group.membership' => 'required|string',
            'group.category_id' => 'required|numeric',
            'group.course_id' => 'required|numeric',
            'unenrollFrom' => 'array',
            'unenrollFrom.unenrollmentIds' => 'array' ,
            'unenrollFrom.unenrollmentIds.*' => 'numeric' ,
        ];
    }
}
