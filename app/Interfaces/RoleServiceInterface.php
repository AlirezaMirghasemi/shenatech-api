<?php

namespace App\Interfaces;

use App\Http\Resources\PermissionCollection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection;


interface RoleServiceInterface
{
    public function getAllRoles(int $perPage);
    public function findRoleById(int $id): Role;
    public function createRole(array $data): Role;
    public function updateRole(int $id, array $data): Role;
    public function deleteRole(int $id): bool;
    public function assignPermissionsToRole(int $roleId, array $permissionIds): Role;
    public function getRolePermissions(Role $role, int $perPage): JsonResponse;
    public function getRoleUsers(Role $role, int $perPage): JsonResponse;
    public function getNotRolePermissions(int $roleId): JsonResponse;
    public function revokePermissionsFromRole(int $roleId, array $permissions): Role;
    public function isUniqueRoleName(string $roleName): bool;
    public function assignUsersToRole(int $roleId, array $users): Role;
    public function revokeUsersFromRole(int $roleId, array $users): Role;
}
