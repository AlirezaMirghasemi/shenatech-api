<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = $this->route('role');
        return $this->user()->can('update', $role);
    }

    public function rules(): array
    {
        $roleId = $this->route('role')->id ?? null;
        return [
            'name' => "required|string|max:255|unique:roles,name,{$roleId}",
        ];
    }
}
