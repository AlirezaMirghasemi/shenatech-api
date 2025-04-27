<?php
namespace App\Http\Requests\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool { return true; } // Handled in Service

    public function rules(): array
    {
        $roleId = $this->route('role') instanceof \Spatie\Permission\Models\Role
                ? $this->route('role')->id
                : $this->route('role'); // Adjust based on route binding

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('roles')->ignore($roleId)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }
}
