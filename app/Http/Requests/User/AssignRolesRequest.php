<?php
namespace App\Http\Requests\User;
use Illuminate\Foundation\Http\FormRequest;

class AssignRolesRequest extends FormRequest
{
    public function authorize(): bool { return true; } // Auth handled in Service

    public function rules(): array
    {
        return [
            'roles' => ['required', 'array'],
            'roles.*' => ['string', 'exists:roles,name'], // Validate each role name exists
        ];
    }
}
