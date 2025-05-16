<?php

namespace App\Repositories;

use App\Interfaces\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function getAllPermissions()
    {
        return Permission::query();
    }

    // Permissions are usually seeded and not managed via typical CRUD API endpoints.
    // Methods like findPermissionById, findPermissionByName, createPermission, etc.,
    // are generally not needed at the Repository level for API management.
    // If you need to manage permissions dynamically, you would add them here.
}
