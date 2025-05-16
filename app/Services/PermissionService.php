<?php

namespace App\Services;

use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\PermissionServiceInterface;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate; // For authorization

class PermissionService implements PermissionServiceInterface
{
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getAllPermissions(int $perPage = 10)
    {
        // Authorization Check
        if (Gate::denies('view permissions')) {
            throw new AuthorizationException('You do not have permission to view permissions.');
        }
        $query = $this->permissionRepository->getAllPermissions();
        return $query->paginate($perPage);
    }

    // As permissions are typically seeded, methods for creating, updating, or deleting
    // are usually not exposed via the API through a service like this.
    // If needed, you would add them here with appropriate authorization.
}
