<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'group' => 'required|json',
            'unenrollFrom' => 'required|json',
        ];
    }
}
