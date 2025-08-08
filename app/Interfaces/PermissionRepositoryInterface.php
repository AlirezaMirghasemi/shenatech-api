<?php

namespace App\Interfaces;

use App\Models\Permission;
use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    public function paginateWithFilters(array $filters);

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
    public function restore(int $id);
    public function existsByName(string $name): bool;


    public function fetchPaginateAssignedRoles(Permission $permission, array $filters);

    public function fetchUnAssignedRoles(array $assignedRoleIds);
    public function fetchPaginateAssignedUsers(Permission $permission, array $filters);


    public function fetchUnAssignedUsers(array $assignedUserIds);
}
