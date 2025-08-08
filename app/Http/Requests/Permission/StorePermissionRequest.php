<?php

namespace App\Http\Requests\Permission;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Permission::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:permissions,name|max:255',
        ];
    }
}
