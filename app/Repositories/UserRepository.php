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
use App\Enums\ImageType;
use Spatie\Permission\Models\Role;

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
        try {
            $path = $image->storeAs($directory, $filename, $disk);


        } catch (\Exception $e) {
            \Log::error("File storage failed: " . $e->getMessage());
            return null;
        }


        if ($user->profileImage) {
            Storage::disk($user->profileImage->disk)->delete($user->profileImage->path);
            $user->profileImage()->delete();
        }

        // 5. Create a new record in the images table
        $newImage = Image::create([
            'title' => $filename,
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
    public function isUnique(string $fieldName, string $fieldValue): bool
    {
        return !User::where($fieldName, $fieldValue)->exists();
    }
    public function getUnAssignedRoleUsers(Role $role): Collection
    {
        $users = User::whereNotIn('id', $role->users()->pluck('id'))->get();
        return $users;
    }

}
