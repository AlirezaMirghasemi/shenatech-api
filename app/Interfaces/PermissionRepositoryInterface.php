<?php
namespace App\Interfaces;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    public function getAllPermissions();
    public function isUniquePermissionName(string $permissionName): bool;
    public function createPermission(array $data): Permission;
    public function findPermissionById(int $id): ?Permission;
    public function deletePermissions(array $permissions): bool;
    public function revokeRolesFromPermission(Permission $permission, array $roleIds): bool;

    // Permissions are usually seeded and managed via Role/Seeder, so basic CRUD might not be exposed via API
    // public function findPermissionById(int $id): ?Permission;
    // public function findPermissionByName(string $name): ?Permission;
}
