<?php
namespace App\Http\Requests\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File; // Rule for file validation

class UploadProfileImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    } // Auth handled in Service

    public function rules(): array
    {
        return [
            'profile_image' => [
                'required',
                File::image() // Ensure it's an image
                    ->max(2 * 1024) // Max size 2MB (adjust as needed)
                // ->dimensions(Rule::dimensions()->maxWidth(1000)->maxHeight(1000)), // Optional dimensions
            ],
        ];
    }
    /**
     * Get custom attributes for validator errors.
     * Rename profile_image to something more user-friendly in errors.
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'profile_image' => 'profile picture',
        ];
    }
}
