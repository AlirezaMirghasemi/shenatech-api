<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\IndexPermissionRequest;
use App\Http\Requests\Permission\RevokePermissionRolesRequest;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Interfaces\PermissionServiceInterface; // Inject Service Interface
use App\Http\Resources\PermissionResource; // Permission Resource
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; // For status codes

class PermissionController extends Controller
{
    protected PermissionServiceInterface $permissionService;

    public function __construct(PermissionServiceInterface $permissionService)
    {
        $this->permissionService = $permissionService;
    }



    /* #region CRUD */
    public function index(IndexPermissionRequest $request)
    {
        $filters = $request->validated();

        $permissions = $this->permissionService->paginateWithFilters($filters);
        return PermissionResource::collection($permissions);
    }

    public function show(int $id): JsonResponse
    {
        $permission = $this->permissionService->findById($id);
        return response()->json(new PermissionResource($permission));
    }

    public function store(StorePermissionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $permission = $this->permissionService->create($data);

        return response()->json([
            'message' => __('messages.permissions.created'),
            'data' => new PermissionResource($permission),
        ], 201);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        $data = $request->validated();
        $permission = $this->permissionService->update($permission->id, $data);
        return response()->json([
            'message' => __('messages.permissions.updated'),
            'data' => new PermissionResource($permission),
        ]);
    }


    public function destroy(Permission $permission): JsonResponse
    {
        $this->permissionService->delete($permission->id);
        return response()->json([
            'message' => __('messages.permissions.deleted'),
        ]);
    }

    public function restore(Permission $permission): JsonResponse
    {
        $this->permissionService->restore($permission->id);
        return response()->json([
            'message' => __('messages.permissions.restored'),
        ]);
    }

    public function isUnique(string $permissionName): JsonResponse
    {
        $isUnique = $this->permissionService->isUnique($permissionName);
        return response()->json(['unique' => $isUnique]);
    }
    /* #endregion */

    /* #region Assign Roles */
    public function assignRoles(Permission $permission): JsonResponse
    {
        $roles = request()->input('roles', []);
        $this->permissionService->assignRoles($permission->id, $roles);

        return response()->json([
            'message' => __('messages.permission.roles_assigned'),
        ]);

    }

    public function revokeRoles(Permission $permission): JsonResponse
    {
        $roles = request()->input('roles', []);

        $this->permissionService->revokeRoles($permission->id, $roles);

        return response()->json([
            'message' => __('messages.permissions.roles_revoked'),
        ]);

    }
    /* #endregion */

    /* #region Fetch Permission Roles  */
    public function fetchAssignedRoles(Permission $permission, IndexPermissionRequest $request)
    {
        $roles = $this->permissionService->fetchAssignedRoles($permission->id, $request->validated());
        return RoleResource::collection($roles);

    }

    public function fetchUnAssignedRoles(Permission $permission): JsonResponse
    {
        $roles = $this->permissionService->fetchUnassignedRoles($permission->id);
        return response()->json([
            'data' => $roles,
        ]);
    }
    /* #endregion */
    /* #region Fetch Permission Users */
    public function fetchAssignedUsers(Permission $permission, IndexPermissionRequest $request)
    {
        $users = $this->permissionService->fetchAssignedUsers($permission->id, $request->validated());
        return UserResource::collection($users);

    }
    public function fetchUnAssignedUsers(Permission $permission): JsonResponse
    {
        $users = $this->permissionService->fetchUnassignedUsers($permission->id);
        return response()->json([
            'data' => $users,
        ]);
    }
    /* #endregion */
}
