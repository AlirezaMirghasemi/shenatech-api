<?php

namespace App\Repositories;

use App\Enums\CommonStatus;
use App\Interfaces\RoleRepositoryInterface;
use App\Models\Role;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleRepository implements RoleRepositoryInterface
{
    public function getAllRoles(array $relations = [])
    {
        if (auth()->user()->roles()->where('name', 'Admin')->exists())
            return Role::with($relations)->orderBy('updated_at', 'desc')->withTrashed();
        else
            return Role::with($relations)->orderBy('updated_at', 'desc');
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
        $role->status = CommonStatus::DELETED;
        $role->deleted_by = auth()->user()->id;
        $role->update();
        $role->delete();
        return $role->delete();
    }

    public function isUniqueRoleName(string $roleName): bool
    {
        $response = Role::where('name', $roleName)->withTrashed()->exists();
        return !$response;
    }
    public function assignUsersToRole(Role $role, array $users): void
    {
        $role->users()->syncWithoutDetaching($users);
        $role->touch();
    }
    public function revokeUsersFromRole(Role $role, array $users): void
    {
        $role->users()->detach($users);
    }
}
