<?php
namespace App\Http\Requests\User;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Enums\UserGender;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule; // For unique rule ignoring current user

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization handled in Service
        return true;
    }

    public function rules(): array
    {
        // Get user ID from route parameter (assuming route model binding or explicit param)
        $userId = $this->route('user') instanceof User
                ? $this->route('user')->id
                : $this->route('id'); // Adjust based on your route definition ('user' or 'id')


        return [
            // Use sometimes to only validate if present
            'username' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('users')->ignore($userId)],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:100', Rule::unique('users')->ignore($userId)],
            // Password is optional on update, but if present, requires confirmation and strength
            'password' => ['nullable', 'string', 'confirmed', Password::defaults()],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'mobile' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('users')->ignore($userId)],
            'gender' => ['nullable', new Enum(UserGender::class)],
            'bio' => ['nullable', 'string'],
             'roles' => ['nullable', 'array'], // Validate roles array if present
             'roles.*' => ['string', 'exists:roles,name'], // Ensure each role exists
        ];
    }
}
