<?php
namespace App\Http\Requests\Permission;
use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool { return true; } // Handled in Service

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'guard_name' => [ 'string', 'max:255','default:web'],
        ];
    }
}
