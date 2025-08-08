<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleRepository implements RoleRepositoryInterface
{
    protected Role $model;
    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    /* #region CRUD */
    public function paginateWithFilters(array $filters): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $perPage = $filters['per_page'] ?? 15;
        $page = $filters['page'] ?? 1;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function find(int $id): ?Role
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Role
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Role
    {
        $role = $this->find($id);
        $role->update($data);

        return $role;
    }

    public function delete(int $id): void
    {
        $role = $this->find($id);
        $role->delete();
    }
    public function restore(int $id): void
    {
        $role = $this->model->withTrashed()->findOrFail($id);
        $role->restore();
    }

    public function existsByName(string $name): bool
    {
        return $this->model->where('name', $name)->exists();
    }
    /* #endregion */

    public function fetchPaginateAssignedPermissions(Role $role, array $filters)
    {
        $query = $role->permissions();
        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }
        return $query->paginate($filters['per_page'] ?? 15, ['*'], 'page', $filters['page'] ?? 1);
    }
    public function fetchUnAssignedPermissions(array $assignedPermissionIds)
    {
        return Permission::whereNotIn('id', $assignedPermissionIds)->get();
    }

    public function fetchPaginateAssignedUsers(Role $role, array $filters)
    {
        $query = $role->users();
        if (!empty($filters['search'])) {
            $query->where('username', 'like', "%{$filters['search']}%");
        }
        return $query->paginate($filters['per_page'] ?? 15, ['*'], 'page', $filters['page'] ?? 1);
    }

    public function fetchUnAssignedUsers(array $assignedUserIds)
    {
        return User::whereNotIn('id', $assignedUserIds)->get();
    }
}
