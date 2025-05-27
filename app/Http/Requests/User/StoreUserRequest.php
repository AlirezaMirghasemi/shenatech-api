<?php
namespace App\Http\Requests\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Enums\UserGender;
use App\Enums\UserStatus;
use Illuminate\Validation\Rules\Enum;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization is handled in the Service layer using Gate
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'status' => ['required', new Enum(UserStatus::class)],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'mobile' => ['required', 'string', 'max:20', 'unique:users,mobile'],
            'gender' => ['nullable', new Enum(UserGender::class)],
            'bio' => ['nullable', 'string'],
            'roles' => ['nullable', 'array'], // Validate roles array
            'roles.*' => ['string', 'exists:roles,name'], // Ensure each role exists
        ];
    }
}
