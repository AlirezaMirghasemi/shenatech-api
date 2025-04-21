<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('edit own profile', $this["user"]) || Gate::allows('manage users');
    }

    public function rules()
    {
        return [
            'username' => 'sometimes|string|max:50|unique:users,username,' . $this["user"],
            'email' => 'sometimes|email|max:100|unique:users,email,' . $this["user"],
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'mobile' => 'nullable|string|max:20|unique:users,mobile,' . $this["user"],
            'bio' => 'nullable|string',
        ];
    }

}
