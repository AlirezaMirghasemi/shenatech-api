<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $permission = $this->route('permission');
        return $this->user()->can('update', $permission);
    }

    public function rules(): array
    {
        $permissionId = $this->route('permission')->id ?? null;
        return [
            'name' => "required|string|max:255|unique:permissions,name,{$permissionId}",
        ];
    }
}
