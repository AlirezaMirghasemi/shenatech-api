<?php

namespace App\Services;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\PermissionRepositoryInterface; // Inject PermissionRepository
use App\Interfaces\RoleServiceInterface;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection as SupportCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate; // For authorization
use Illuminate\Validation\ValidationException; // For permission validation
use Spatie\Permission\Models\Permission; // Import Permission model

class RoleService implements RoleServiceInterface
{
    protected $roleRepository;
    protected $permissionRepository;

    public function __construct(RoleRepositoryInterface $roleRepository, PermissionRepositoryInterface $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function getAllRoles(int $perPage = 10)
    {
        // Authorization Check
        if (Gate::denies('view roles')) {
            throw new AuthorizationException('You do not have permission to view roles.');
        }

        $query = $this->roleRepository->getAllRoles(['permissions']);

        return $query->paginate($perPage);
    }

    public function findRoleById(int $id): Role
    {
        // Authorization Check (view single role also requires 'view roles')
        if (Gate::denies('view roles')) {
            throw new AuthorizationException('You do not have permission to view roles.');
        }

        $role = $this->roleRepository->findRoleById($id, ['permissions']);
        if (!$role) {
            throw new NotFoundHttpException("Role with ID {$id} not found.");
        }
        return $role;
    }

    public function createRole(array $data): Role
    {
        // Authorization Check
        if (Gate::denies('manage roles')) {
            throw new AuthorizationException('You do not have permission to create roles.');
        }

        // Validate and assign permissions if provided
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']); // Remove permissions from role data

        $role = $this->roleRepository->createRole($data);

        if (!empty($permissions)) {
            $this->assignPermissionsToRole($role->id, $permissions); // Use the service method
        }

        return $role->load('permissions'); // Load permissions for response
    }

    public function updateRole(int $id, array $data): Role
    {
        $role = $this->findRoleById($id); // Handles NotFoundException and initial Authorization

        // Additional Authorization Check if specific updates are restricted
        if (Gate::denies('manage roles')) {
            throw new AuthorizationException('You do not have permission to update roles.');
        }

        // Validate and assign permissions if provided
        $permissions = $data['permissions'] ?? null; // Use null to differentiate between not provided and empty array
        unset($data['permissions']);

        $this->roleRepository->updateRole($role, $data);

        // Handle permission assignment separately if permissions were provided in the update data
        if (is_array($permissions)) {
            $this->assignPermissionsToRole($role->id, $permissions);
        }


        return $role->fresh(['permissions']); // Refresh model and load permissions
    }

    public function deleteRole(int $id): bool
    {
        // Authorization Check
        if (Gate::denies('manage roles')) {
            throw new AuthorizationException('You do not have permission to delete roles.');
        }

        $role = $this->findRoleById($id); // Handles NotFoundException

        // Prevent deleting crucial roles like 'Admin' or 'Viewer' (Optional business logic)
        if (in_array($role->name, ['Admin', 'Viewer'])) {
            throw new AuthorizationException("You cannot delete the {$role->name} role.");
        }

        return $this->roleRepository->deleteRole($role);
    }

    public function assignPermissionsToRole(int $roleId, array $permissions): Role
    {
        $role = $this->findRoleById($roleId); // Handles NotFoundException and initial Auth

        // Authorization Check
        if (Gate::denies('manage roles')) { // Or a more specific permission like 'assign permissions to roles'
            throw new AuthorizationException('You do not have permission to assign permissions to roles.');
        }

        // Validate that permissions exist
        $validPermissions = Permission::whereIn('name', $permissions)->pluck('name')->toArray();
        if (count($validPermissions) !== count($permissions)) {
            $invalidPermissions = array_diff($permissions, $validPermissions);
            throw ValidationException::withMessages([
                'permissions' => ['Invalid permissions provided: ' . implode(', ', $invalidPermissions)],
            ]);
        }


        // Use syncPermissions to assign the exact permissions provided, removing any others
        $role->syncPermissions($validPermissions);

        return $role->load('permissions'); // Load permissions for response
    }
    public function getRolePermissions(Role $role, int $perPage = 10): JsonResponse
    {
        if (Gate::denies('view roles') || Gate::denies('view permissions')) {
            throw new AuthorizationException('You do not have permission to view role permissions.');
        }
        $permissions = $role->permissions()->paginate($perPage);
        return PermissionResource::collection($permissions)->response();
    }
    public function getRoleUsers(Role $role, int $perPage = 10): JsonResponse
    {

        if (Gate::denies('view roles') || Gate::denies('view users')) {
            throw new AuthorizationException('You do not have permission to view role users.');
        }
        $users = $role->users()->paginate($perPage);
        return UserResource::collection($users)->response();
    }
}
