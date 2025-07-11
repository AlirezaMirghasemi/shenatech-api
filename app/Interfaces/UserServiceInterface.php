<?php
namespace App\Interfaces;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection as SupportCollection;
use Spatie\Permission\Models\Role;

interface UserServiceInterface
{
    public function getAllUsers(array $filters = []): SupportCollection;
    public function getUserById(int $id): ?User;
    public function createUser(array $userData): User;
    public function updateUser(int $id, array $userData, ?UploadedFile $image = null): User;
    public function deleteUser(int $id, bool $removeProfilePicture): bool;
    public function uploadProfileImage(int $userId, UploadedFile $image): User;
    public function assignRolesToUser(int $userId, array $roles): User;
    public function isUnique(string $fieldName, string $fieldValue): bool;
    public function getUnAssignedRoleUsers(Role $role): SupportCollection;
}
