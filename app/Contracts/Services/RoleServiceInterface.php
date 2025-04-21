<?php

namespace App\Contracts\Services;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleServiceInterface
{
    public function getAllRoles(): Collection;
    public function getRoleById(int $id): ?Role;
    public function createRole(array $data): Role;
    public function updateRole(int $id, array $data): Role;
    public function deleteRole(int $id): bool;
}
