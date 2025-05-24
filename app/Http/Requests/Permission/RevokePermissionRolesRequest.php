<?php
namespace App\Http\Requests\Permission;
use Illuminate\Foundation\Http\FormRequest;

class RevokePermissionRolesRequest extends FormRequest
{
    public function authorize(): bool { return true; } // Handled in Service

    public function rules(): array
    {
        return [
            'roleIds' => ['required', 'array'],
            'roleIds.*' => ['integer', 'exists:roles,id'],
        ];
    }
}
