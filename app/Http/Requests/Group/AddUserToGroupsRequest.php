<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class AddUserToGroupsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'county' => 'required|array',
            'county.name' => 'required|string',
            'county.description' => 'required|string',
            'community' => 'required|array',
            'community.name' => 'required|string',
            'community.description' => 'required|string',
            'school' => 'required|array',
            'school.name' => 'required|string',
            'school.description' => 'required|string',
            'faculty' => 'string|nullable',
        ];
    }
}
