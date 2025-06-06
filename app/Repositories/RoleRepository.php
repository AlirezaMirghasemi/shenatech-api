<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleRepository implements RoleRepositoryInterface
{
    public function getAllRoles(array $relations = [])
    {
        return Role::with($relations);
    }

    public function findRoleById(int $id, array $relations = []): ?Role
    {
        return Role::with($relations)->find($id);
    }

    public function findRoleByName(string $name, array $relations = []): ?Role
    {
        return Role::with($relations)->where('name', $name)->first();
    }

    public function createRole(array $data): Role
    {
        return Role::create($data);
    }

    public function updateRole(Role $role, array $data): bool
    {
        return $role->update($data);
    }

    public function deleteRole(Role $role): bool
    {
        return $role->delete();
    }

    public function isUniqueRoleName(string $roleName): bool
    {
        return Role::where('name', $roleName)->doesntExist();
    }
    public function assignUsersToRole(Role $role, array $users): void
    {
        $role->users()->sync($users);
    }
}
