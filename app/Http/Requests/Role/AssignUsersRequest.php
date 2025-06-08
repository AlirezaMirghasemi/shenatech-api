<?php
namespace App\Http\Requests\Role;
use Illuminate\Foundation\Http\FormRequest;

class AssignUsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    } // Handled in Service

    public function rules(): array
    {
        return [
            'userIds' => ['required', 'array'],
            'userIds.*' => ['integer', 'exists:users,id'],
        ];
    }
}
