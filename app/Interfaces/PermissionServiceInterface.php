<?php

namespace App\Interfaces;

use Illuminate\Support\Collection as SupportCollection;
use Spatie\Permission\Models\Permission;

interface PermissionServiceInterface
{
    public function getAllPermissions(int $perPage);
    public function findPermissionById(int $id): Permission;
    public function createPermission(array $data): Permission;
    public function assignRolesToPermission(int $permissionId, array $roleIds): Permission;
    public function isUniquePermissionName(string $permissionName): bool;
    public function deletePermission(Permission $permission): bool;
    // Methods for managing permissions directly via API could be added here if needed,
    // but it's generally safer to manage them via roles or seeders.
}
