<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreRoleRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('manage roles');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ];
    }
}
