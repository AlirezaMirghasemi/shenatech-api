<?php

namespace App\Interfaces;

use App\Models\Permission;
use Illuminate\Support\Collection as SupportCollection;

interface PermissionServiceInterface
{
    public function getAllPermissions(int $perPage,string $search=null);
    public function findPermissionById(int $id): Permission;
    public function createPermission(array $data): Permission;
    public function assignRolesToPermission(int $permissionId, array $roleIds): Permission;
    public function isUniquePermissionName(string $permissionName): bool;
    public function deletePermissions(array $permissions): bool;
    public function getPermissionRoles(Permission $permission, int $perPage);
    public function getPermissionUsers(Permission $permission, int $perPage);

    public function revokeRolesFromPermission(int $permissionId, array $roleIds);
    // Methods for managing permissions directly via API could be added here if needed,
    // but it's generally safer to manage them via roles or seeders.
}
