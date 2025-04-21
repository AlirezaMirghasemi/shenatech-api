<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('create users');
    }

    public function rules()
    {
        return [
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'mobile' => 'nullable|string|max:20|unique:users',
        ];
    }
}
