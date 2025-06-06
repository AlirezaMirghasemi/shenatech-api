<?php
namespace App\Interfaces;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    public function getAllRoles(array $relations = []);
    public function findRoleById(int $id, array $relations = []): ?Role;
    public function findRoleByName(string $name, array $relations = []): ?Role;
    public function createRole(array $data): Role;
    public function updateRole(Role $role, array $data): bool;
    public function deleteRole(Role $role): bool;
    public function isUniqueRoleName(string $roleName): bool;
    public function assignUsersToRole(Role $role, array $users): void;
}
