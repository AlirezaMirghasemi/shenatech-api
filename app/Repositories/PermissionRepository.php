<?php

namespace App\Repositories;

use App\Interfaces\PermissionRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function getAllPermissions()
    {
        return Permission::query();
    }

    // Permissions are usually seeded and not managed via typical CRUD API endpoints.
    // Methods like findPermissionById, findPermissionByName, createPermission, etc.,
    // are generally not needed at the Repository level for API management.
    // If you need to manage permissions dynamically, you would add them here.
    public function findPermissionById(int $id): ?Permission
    {
        return Permission::find($id);
    }

    public function createPermission(array $data): Permission
    {
        return Permission::create($data);
    }

    public function isUniquePermissionName(string $permissionName): bool
    {
        return Permission::where('name', $permissionName)->doesntExist();
    }

    public function deletePermission(Permission $permission): bool
    {
        return $permission->delete();
    }
}
