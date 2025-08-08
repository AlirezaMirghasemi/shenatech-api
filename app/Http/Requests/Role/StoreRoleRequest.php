<?php

namespace App\Http\Requests\Role;

use App\Enums\CommonStatus;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Role::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:roles,name|max:255',
        ];
    }
}
