<?php
namespace App\Interfaces;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;

interface UserRepositoryInterface
{
    public function getAllUsers(array $filters = [], array $relations = []): Collection;
    public function findUserById(int $id, array $relations = []): ?User;
    public function findUserByEmail(string $email): ?User;
    public function createUser(array $data): User;
    public function updateUser(User $user, array $data): bool;
    public function deleteUser(User $user,array $options): bool;
    public function updateUserProfileImage(User $user, UploadedFile $image): ?string; // Returns image path or null
    public function isUnique(string $fieldName, string $fieldValue): bool;
    public function getUnAssignedRoleUsers(Role $role): Collection;
}
