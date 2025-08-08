<?php

namespace App\Repositories;

use App\Interfaces\PermissionRepositoryInterface;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionRepository implements PermissionRepositoryInterface
{
    protected Permission $model;
    public function __construct(Permission $model)
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

    public function find(int $id): ?Permission
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Permission
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Permission
    {
        $permission = $this->find($id);
        $permission->update($data);

        return $permission;
    }

    public function delete(int $id): void
    {
        $permission = $this->find($id);
        $permission->delete();
    }
    public function restore(int $id): void
    {
        $permission = $this->model->withTrashed()->findOrFail($id);
        $permission->restore();
    }

    public function existsByName(string $name): bool
    {
        return $this->model->where('name', $name)->exists();
    }
    /* #endregion */

    public function fetchPaginateAssignedRoles(Permission $permission, array $filters)
    {
        $query = $permission->roles();
        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }
        return $query->paginate($filters['per_page'] ?? 15, ['*'], 'page', $filters['page'] ?? 1);
    }
    public function fetchUnAssignedRoles(array $assignedRoleIds)
    {
        return Role::whereNotIn('id', $assignedRoleIds)->get();
    }

    public function fetchPaginateAssignedUsers(Permission $permission, array $filters)
    {
        $query = $permission->users();
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
