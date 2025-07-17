<?php

namespace App\Services;

use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\PermissionServiceInterface;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate; // For authorization
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;


class PermissionService implements PermissionServiceInterface
{
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getAllPermissions(int $perPage = 10, string $search = null)
    {
        if (Gate::denies('view permissions')) {
            throw new AuthorizationException('You do not have permission to view permissions.');
        }
        if ($search == null) {
            return $this->permissionRepository->getAllPermissions()->paginate($perPage);
        } else {
            $query = $this->permissionRepository->getAllPermissions()->where('name', 'like', "%{$search}%");
            return $query->paginate($perPage);
        }
    }



    public function findPermissionById(int $id): Permission
    {
        if (Gate::denies('view permissions')) {
            throw new AuthorizationException('You do not have permission to view permissions.');
        }

        $permission = $this->permissionRepository->findPermissionById($id);
        if (!$permission) {
            throw new NotFoundHttpException("Permission with ID {$id} not found.");
        }
        return $permission;
    }
    public function createPermission(array $data): Permission
    {
        if (Gate::denies('manage permissions')) {
            throw new AuthorizationException('You do not have permission to create permissions.');
        }
        $permission = $this->permissionRepository->createPermission($data);

        if (!empty($permissions)) {
            $this->assignRolesToPermission($permission->id, $permissions);
        }

        return $permission->load('permissions');
    }
    public function assignRolesToPermission(int $permissionId, array $roleIds): Permission
    {
        $permission = $this->findPermissionById($permissionId);

        // Authorization Check
        if (Gate::denies('manage permissions')) {
            throw new AuthorizationException('You do not have permission to assign roles to permission.');
        }

        $validRoles = Role::whereIn('id', $roleIds)->pluck('id')->toArray();
        if (count($validRoles) !== count($roleIds)) {
            $invalidRoles = array_diff($roleIds, $validRoles);
            throw ValidationException::withMessages([
                'roles' => ['Invalid roles provided: ' . implode(', ', $invalidRoles)],
            ]);
        }

        $permission->giveRoleTo($validRoles);
        $permission->touch();
        return $permission->load('roles');
    }
    public function isUniquePermissionName(string $permissionName): bool
    {
        $permissionName = trim($permissionName);
        return $this->permissionRepository->isUniquePermissionName($permissionName);
    }
    public function deletePermissions(array $permissions): bool
    {
        if (Gate::denies('manage permissions')) {
            throw new AuthorizationException('You do not have permission to delete permissions.');
        }
        $roles = Role::whereHas('permissions', function ($query) use ($permissions) {
            $query->whereIn('permissions.id', $permissions);
        })->get();

        if ($roles->count() > 0) {
            throw new AuthorizationException("You cannot delete a permission that has assigned roles.");
        }
        return $this->permissionRepository->deletePermissions($permissions);
    }
    public function getPermissionRoles(Permission $permission, int $perPage = 10)
    {
        if (Gate::denies('view permissions')) {
            throw new AuthorizationException('You do not have permission to view permission roles.');
        }
        $roles = $permission->roles()->paginate($perPage);
        return RoleResource::collection($roles)->response();
    }
    public function getPermissionUsers(Permission $permission, int $perPage = 10)
    {
        if (Gate::denies('view permissions')) {
            throw new AuthorizationException('You do not have permission to view permission users.');
        }

        // Get all roles for the permission, then get users for those roles
        $roleIds = $permission->roles()->pluck('roles.id');
        $usersQuery = User::whereHas('roles', function ($query) use ($roleIds) {
            $query->whereIn('roles.id', $roleIds);
        })->distinct();

        $users = $usersQuery->paginate($perPage);

        return UserResource::collection($users)->response();
    }
    public function revokeRolesFromPermission(int $permissionId, array $roleIds)
    {
        $permission = $this->findPermissionById($permissionId);
        if (Gate::denies('manage permissions')) {
            throw new AuthorizationException('You do not have permission to revoke roles from permission.');
        }
        $validRoles = Role::whereIn('id', $roleIds)->pluck('id')->toArray();
        if (count($validRoles) !== count($roleIds)) {
            $invalidRoles = array_diff($roleIds, $validRoles);
            throw ValidationException::withMessages([
                'roles' => ['Invalid roles provided: ' . implode(', ', $invalidRoles)],
            ]);
        }
        return $this->permissionRepository->revokeRolesFromPermission($permission, $validRoles);
    }
}
