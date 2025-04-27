<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\PermissionServiceInterface; // Inject Service Interface
use App\Http\Resources\PermissionResource; // Permission Resource
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionServiceInterface $permissionService)
    {
        $this->permissionService = $permissionService;
         // Authorization is primarily handled within the Service layer methods
        // $this->middleware('permission:view permissions')->only(['index']);
    }

    /**
     * Display a listing of the resource (Permissions are usually listed, not CRUD via API).
     */
    public function index(): JsonResponse
    {
        $permissions = $this->permissionService->getAllPermissions(); // Service handles authorization
        return PermissionResource::collection($permissions)->response();
    }

    // Typically, methods for creating, showing, updating, or deleting permissions
    // are not needed in an API controller as permissions are managed via seeding.
}
