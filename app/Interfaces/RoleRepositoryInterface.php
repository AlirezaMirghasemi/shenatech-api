<?php

namespace App\Interfaces;

use App\Models\Role;

interface RoleRepositoryInterface
{
    public function paginateWithFilters(array $filters);

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
    public function restore(int $id);
    public function existsByName(string $name): bool;


    public function fetchPaginateAssignedPermissions(Role $role, array $filters);

    public function fetchUnAssignedPermissions(array $assignedPermissionIds);
    public function fetchPaginateAssignedUsers(Role $role, array $filters);


    public function fetchUnAssignedUsers(array $assignedUserIds);


}
