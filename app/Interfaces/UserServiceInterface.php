<?php
namespace App\Interfaces;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection as SupportCollection;

interface UserServiceInterface
{
    public function getAllUsers(int $page, int $perPage, string $search = null);
    public function getUserById(int $id): ?User;
    public function createUser(array $userData): User;
    public function updateUser(int $id, array $userData, ?UploadedFile $image = null): User;
    public function deleteUser(int $id, array $options): bool;
    public function uploadProfileImage(int $userId, UploadedFile $image): User;
    public function assignRolesToUser(int $userId, array $roles): User;
    public function isUnique(string $fieldName, string $fieldValue): bool;
    public function getUnAssignedRoleUsers(Role $role): SupportCollection;
    public function restoreUsers(array $users);

}
