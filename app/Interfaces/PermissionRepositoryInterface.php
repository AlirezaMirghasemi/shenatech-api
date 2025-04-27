<?php
namespace App\Interfaces;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    public function getAllPermissions(): Collection;
    // Permissions are usually seeded and managed via Role/Seeder, so basic CRUD might not be exposed via API
    // public function findPermissionById(int $id): ?Permission;
    // public function findPermissionByName(string $name): ?Permission;
}
