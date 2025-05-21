<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Interfaces\PermissionServiceInterface; // Inject Service Interface
use App\Http\Resources\PermissionResource; // Permission Resource
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response; // For status codes
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
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10); // دریافت تعداد آیتم در هر صفحه
        $permissions = $this->permissionService->getAllPermissions($perPage); // Service handles authorization
        return PermissionResource::collection($permissions)->response();
    }

    // Typically, methods for creating, showing, updating, or deleting permissions
    // are not needed in an API controller as permissions are managed via seeding.

    public function store(StorePermissionRequest $request): JsonResponse
    {
        $permission = $this->permissionService->createPermission($request->validated()); // Service handles authorization and role assignment
        return (new PermissionResource($permission))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED); // 201
    }
    public function isUnique(string $permissionName): bool
    {
        return $this->permissionService->isUniquePermissionName($permissionName);
    }
    public function destroy(Permission $permission): JsonResponse
    {
        $this->permissionService->deletePermission($permission); // Service handles authorization
        return response()->json(null, Response::HTTP_NO_CONTENT); // 204
    }
}
