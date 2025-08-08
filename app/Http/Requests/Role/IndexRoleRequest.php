<?php

namespace App\Http\Requests\Role;

use App\Enums\CommonStatus;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class IndexRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', Role::class);
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'status' => ['nullable', new Enum(CommonStatus::class)],
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }


}
