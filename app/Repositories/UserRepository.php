<?php
namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // For generating file names
use App\Models\Image; // Import Image model
use App\Enums\ImageType; // Import ImageType enum

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers(array $filters = [], array $relations = []): Collection
    {
        // Add filtering logic here if needed based on $filters array
        return User::with($relations)->get();
    }

    public function findUserById(int $id, array $relations = []): ?User
    {
        return User::with($relations)->where('id', $id)->first();
    }

    public function findUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function createUser(array $data): User
    {
        // Ensure password is provided and hashed if creating through here directly
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return User::create($data);
    }

    public function updateUser(User $user, array $data): bool
    {
        // Hash password if it's being updated
        if (isset($data['password'])) {
            // Add validation for password strength/confirmation in the Request/Service layer
            if (!empty($data['password'])) { // Only hash if password is not empty
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']); // Don't update password if empty string is passed
            }
        }
        return $user->update($data);
    }

    public function deleteUser(User $user): bool
    {
        // Soft delete is handled automatically by the model trait
        return $user->delete();
    }

    public function updateUserProfileImage(User $user, UploadedFile $image): ?string
    {
        // 1. Define storage path based on type (profile images)
        $directory = 'images/profiles';
        $disk = 'public'; // Use the public disk

        // 2. Generate a unique file name
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

        // 3. Store the file
        $path = $image->storeAs($directory, $filename, $disk);

        if ($path) {
            // 4. Delete the old image file and model record if it exists
            if ($user->profileImage) {
                Storage::disk($user->profileImage->disk)->delete($user->profileImage->path);
                // Use forceDelete() if Image model also uses SoftDeletes and you want permanent deletion
                $user->profileImage()->dissociate(); // Remove the relationship first
                $user->save(); // Save the user model to clear image_id
                $user->profileImage()->delete(); // Delete the old image model record
            }

            // 5. Create a new record in the images table
            $newImage = Image::create([
                'title' => $image->getClientOriginalName(),
                'type' => ImageType::PROFILE,
                'path' => $path,
                'disk' => $disk,
                'mime_type' => $image->getMimeType(),
                'size' => $image->getSize(),
            ]);

            // 6. Update the user's image_id
            $user->image_id = $newImage->id;
            $user->save();

            return $path; // Return the path of the newly stored image
        }

        return null; // Return null on failure
    }
}
