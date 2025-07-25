<?php

namespace App\Interfaces;

use App\Models\Permission;
use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    public function getAllPermissions();
    public function isUniquePermissionName(string $permissionName): bool;
    public function createPermission(array $data): Permission;
    public function findPermissionById(int $id): ?Permission;
    public function deletePermissions(array $permissions): bool;
    public function revokeRolesFromPermission(Permission $permission, array $roleIds): bool;
    // public function getPermissionUsers(int $permissionId);
}
