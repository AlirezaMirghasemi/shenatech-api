<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateRoleRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('manage roles');
    }

    public function rules()
    {
        return [
            'name' =>
            'sometimes|string|max:255|' .
                'unique:roles,name,' . $this["role"],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ];
    }
}
