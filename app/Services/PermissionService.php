<?php

namespace App\Services;

use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\PermissionServiceInterface;
use App\Models\Role;
use Illuminate\Support\Facades\DB;


class PermissionService implements PermissionServiceInterface
{
    protected PermissionRepositoryInterface $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /* #region CRUD */
    public function paginateWithFilters(array $filters)
    {
        return $this->permissionRepository->paginateWithFilters($filters);
    }

    public function findById(int $id)
    {
        return $this->permissionRepository->find($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->permissionRepository->create($data);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->permissionRepository->update($id, $data);
        });
    }

    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {
            $permission = $this->permissionRepository->find($id);
            $this->permissionRepository->delete($id);
        });
    }

    public function restore(int $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->permissionRepository->restore($id);
        });
    }
    public function isUnique(string $name): bool
    {
        return !$this->permissionRepository->existsByName($name);
    }
    /* #endregion */

    /* #region Assign Roles */
    public function assignRoles(int $id, array $roles)
    {
        return DB::transaction(function () use ($id, $roles) {
            $permission = $this->permissionRepository->find($id);
            foreach ($roles as $key => $value) {
                $role = Role::find($key);
                $role->givePermissionTo($permission);
            }
        });
    }
    public function revokeRoles(int $id, array $roles)
    {
        return DB::transaction(function () use ($id, $roles) {
            $permission = $this->permissionRepository->find($id);
            foreach ($roles as $key => $value) {
                $role = Role::find($key);
                $role->revokePermissionTo($permission);
            }
        });
    }
    /* #endregion */
    /* #region Fetch Permission Roles  */
    public function fetchAssignedRoles(int $id, array $filters)
    {
        $permission = $this->permissionRepository->find($id);
        return $this->permissionRepository->fetchPaginateAssignedRoles($permission, $filters);
    }
    public function fetchUnAssignedRoles(int $id)
    {
        $permission = $this->permissionRepository->find($id);
        $assignedRoles = $permission->role()->pluck('id')->toArray();
        return $this->permissionRepository->fetchUnAssignedRoles($assignedRoles);
    }
    /* #endregion */
    /* #region Fetch Permission Users */
    public function fetchAssignedUsers(int $id, array $filters)
    {
        $permission = $this->permissionRepository->find($id);
        return $this->permissionRepository->fetchPaginateAssignedUsers($permission, $filters);

    }
    public function fetchUnAssignedUsers(int $id)
    {
        $permission = $this->permissionRepository->find($id);
        $assignedUsers = $permission->users()->pluck('id')->toArray();
        return $this->permissionRepository->fetchUnAssignedUsers($assignedUsers);
    }
    /* #endregion */


}
