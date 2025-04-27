<?php
namespace App\Interfaces;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection as SupportCollection;

interface RoleServiceInterface
{
    public function getAllRoles(): SupportCollection;
    public function findRoleById(int $id): Role;
    public function createRole(array $data): Role;
    public function updateRole(int $id, array $data): Role;
    public function deleteRole(int $id): bool;
    public function assignPermissionsToRole(int $roleId, array $permissions): Role;
}
