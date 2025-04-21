<?php

namespace App\Repositories;

use App\Contracts\Repositories\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class EloquentRoleRepository implements RoleRepositoryInterface
{
    public function all(): Collection
    {
        return Role::all();
    }

    public function find(int $id): ?Role
    {
        return Role::find($id);
    }

    public function create(array $data): Role
    {
        return Role::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Role::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Role::destroy($id);
    }
}
