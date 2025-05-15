<?php
namespace App\Http\Requests\Role;
use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionsRequest extends FormRequest
{
    public function authorize(): bool { return true; } // Handled in Service

    public function rules(): array
    {
        return [
            'permissionIds' => ['required', 'array'],
            'permissionIds.*' => ['integer', 'exists:permissions,id'],
        ];
    }
}
