<?php
namespace App\Interfaces;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection as SupportCollection; // Alias Collection

interface UserServiceInterface
{
    public function getAllUsers(array $filters = []): SupportCollection;
    public function getUserById(int $id): ?User;
    public function createUser(array $userData): User;
    public function updateUser(int $id, array $userData): User;
    public function deleteUser(int $id): bool;
    public function uploadProfileImage(int $userId, UploadedFile $image): User;
    public function assignRolesToUser(int $userId, array $roles): User;
}
