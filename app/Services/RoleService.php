<?php

namespace App\Services;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\RoleServiceInterface;
use Illuminate\Support\Facades\DB;
class RoleService implements RoleServiceInterface
{
    protected RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /* #region CRUD */
    public function paginateWithFilters(array $filters)
    {
        return $this->roleRepository->paginateWithFilters($filters);
    }

    public function findById(int $id)
    {
        return $this->roleRepository->find($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->roleRepository->create($data);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->roleRepository->update($id, $data);
        });
    }

    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {
            $role = $this->roleRepository->find($id);
            $this->roleRepository->delete($id);
        });
    }

    public function restore(int $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->roleRepository->restore($id);
        });
    }
    public function isUnique(string $name): bool
    {
        return !$this->roleRepository->existsByName($name);
    }
    /* #endregion */

    /* #region Assign Permissions */
    public function assignPermissions(int $id, array $permissions)
    {
        return DB::transaction(function () use ($id, $permissions) {
            $role = $this->roleRepository->find($id);
            $role->givePermissionTo($permissions);
        });
    }
    public function revokePermissions(int $id, array $permissions)
    {
        return DB::transaction(function () use ($id, $permissions) {
            $role = $this->roleRepository->find($id);
            return $role->permissions()->detach($permissions);
        });
    }
    /* #endregion */
    /* #region Fetch Role Permissions  */
    public function fetchAssignedPermissions(int $id, array $filters)
    {
        $role = $this->roleRepository->find($id);
        return $this->roleRepository->fetchPaginateAssignedPermissions($role, $filters);
    }
    public function fetchUnAssignedPermissions(int $id)
    {
        $role = $this->roleRepository->find($id);
        $assignedPermissions = $role->permissions()->pluck('id')->toArray();
        return $this->roleRepository->fetchUnAssignedPermissions($assignedPermissions);
    }
    /* #endregion */




    /* #region Assign Users */
    public function assignUsers(int $id, array $users)
    {
        return DB::transaction(function () use ($id, $users) {
            $role = $this->roleRepository->find($id);
            $role->syncUsers($users);
        });
    }
    public function revokeUsers(int $id, array $users)
    {
        return DB::transaction(function () use ($id, $users) {
            $role = $this->roleRepository->find($id);
            return $role->users()->detach($users);
        });
    }
    /* #endregion */
    /* #region Fetch Role Users */
    public function fetchAssignedUsers(int $id, array $filters)
    {
        $role = $this->roleRepository->find($id);
        return $this->roleRepository->fetchPaginateAssignedUsers($role, $filters);

    }
    public function fetchUnAssignedUsers(int $id)
    {
        $role = $this->roleRepository->find($id);
        $assignedUsers = $role->users()->pluck('id')->toArray();
        return $this->roleRepository->fetchUnAssignedUsers($assignedUsers);
    }
    /* #endregion */






}
