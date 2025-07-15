<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\RevokePermissionRolesRequest;
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


    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $search=$request->input('search',null);
        $permissions = $this->permissionService->getAllPermissions($perPage,$search);

        return PermissionResource::collection($permissions)->response();
    }
    public function store(StorePermissionRequest $request): JsonResponse
    {
        $permission = $this->permissionService->createPermission($request->validated());
        return (new PermissionResource($permission))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED); // 201
    }
    public function isUnique(string $permissionName): bool
    {
        return $this->permissionService->isUniquePermissionName($permissionName);
    }
    public function destroy(Request $request): JsonResponse
    {
        $permissions = $request->input('permissionIds', []);
        $this->permissionService->deletePermissions($permissions); // Service handles authorization
        return response()->json(null, Response::HTTP_NO_CONTENT); // 204
    }
    public function getPermissionRoles(Permission $permission, Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $rolesResponse = $this->permissionService->getPermissionRoles($permission, $perPage);
        return $rolesResponse;
    }
    public function getPermissionUsers(Permission $permission, Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $users = $this->permissionService->getPermissionUsers($permission, $perPage);
        return $users;
    }
    public function revokeRoles(RevokePermissionRolesRequest $request, Permission $permission): JsonResponse
    {
        $this->permissionService->revokeRolesFromPermission($permission->id, $request->validated('roleIds'));
        return (new PermissionResource($permission->fresh(['roles'])))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
