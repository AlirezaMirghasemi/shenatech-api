<?php

namespace App\Services;

use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\PermissionServiceInterface;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate; // For authorization
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PermissionService implements PermissionServiceInterface
{
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getAllPermissions(int $perPage = 10)
    {
        // Authorization Check
        if (Gate::denies('view permissions')) {
            throw new AuthorizationException('You do not have permission to view permissions.');
        }
        $query = $this->permissionRepository->getAllPermissions();
        return $query->paginate($perPage);
    }

    // As permissions are typically seeded, methods for creating, updating, or deleting
    // are usually not exposed via the API through a service like this.
    // If needed, you would add them here with appropriate authorization.


    public function findPermissionById(int $id): Permission{
        // Authorization Check (view single permission also requires 'view permissions')
        if (Gate::denies('view permissions')) {
            throw new AuthorizationException('You do not have permission to view permissions.');
        }

        $permission = $this->permissionRepository->findPermissionById($id);
        if (!$permission) {
            throw new NotFoundHttpException("Permission with ID {$id} not found.");
        }
        return $permission;
    }
    public function createPermission(array $data): Permission{
        if (Gate::denies('manage permissions')) {
            throw new AuthorizationException('You do not have permission to create permissions.');
        }
        $permission = $this->permissionRepository->createPermission($data);

        if (!empty($permissions)) {
            $this->assignRolesToPermission($permission->id, $permissions); // Use the service method
        }

        return $permission->load('permissions'); // Load roles for response
    }
    public function assignRolesToPermission(int $permissionId, array $roleIds): Permission{
        $permission = $this->findPermissionById($permissionId); // Handles NotFoundException and initial Auth

        // Authorization Check
        if (Gate::denies('manage permissions')) { // Or a more specific permission like 'assign permissions to roles'
            throw new AuthorizationException('You do not have permission to assign roles to permission.');
        }

        // Validate that roles exist
        $validRoles = Role::whereIn('id', $roleIds)->pluck('id')->toArray();
        if (count($validRoles) !== count($roleIds)) {
            $invalidRoles = array_diff($roleIds, $validRoles);
            throw ValidationException::withMessages([
                'roles' => ['Invalid roles provided: ' . implode(', ', $invalidRoles)],
            ]);
        }

        // Use syncRoles to assign the exact roles provided, removing any others
        $permission->giveRoleTo($validRoles);
        $permission->touch();
        return $permission->load('roles'); // Load roles for response
    }
    public function isUniquePermissionName(string $permissionName): bool{
        $permissionName = trim($permissionName);
        return $this->permissionRepository->isUniquePermissionName($permissionName);
    }
    public function deletePermission(Permission $permission): bool{
        if (Gate::denies('manage permissions')) {
            throw new AuthorizationException('You do not have permission to delete permissions.');
        }
        $roles=Role::whereHas('permissions', function ($query) use ($permission) {
            $query->where('permissions.id', $permission->id);
        })->get();

        if ($roles->count() > 0) {
            throw new AuthorizationException("You cannot delete a permission that has assigned roles.");
        }
        return $this->permissionRepository->deletePermission($permission);
    }
}
