<?php
namespace App\Http\Requests\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Enums\UserGender;
use App\Enums\UserStatus;
use Illuminate\Validation\Rules\Enum;

class DeleteUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization is handled in the Service layer using Gate
        return true;
    }

    public function rules(): array
    {
        return [
            'options' => 'required|array',
            'options.*' => 'nullable|boolean'
        ];
    }
}
