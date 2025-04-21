<?php

namespace App\Services;

use App\Contracts\Repositories\RoleRepositoryInterface;
use App\Contracts\Services\RoleServiceInterface;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleService implements RoleServiceInterface
{
    protected $repository;

    public function __construct(RoleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllRoles(): Collection
    {
        return $this->repository->all();
    }

    public function getRoleById(int $id): ?Role
    {
        return $this->repository->find($id);
    }

    public function createRole(array $data): Role
    {
        return $this->repository->create($data);
    }

    public function updateRole(int $id, array $data): Role
    {
        $this->repository->update($id, $data);
        return $this->getRoleById($id);
    }

    public function deleteRole(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
