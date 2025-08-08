<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\AssignUsersRequest;
use App\Http\Requests\Role\IndexRoleRequest;
use App\Http\Requests\Role\RoleRequest;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Requests\Role\AssignPermissionsRequest;
use App\Http\Resources\PermissionCollection;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Interfaces\RoleServiceInterface;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

//IMPORTANT !!!TODO: Refactor role service and role repository
class RoleController extends Controller
{
    protected RoleServiceInterface $roleService;

    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
    }



    /* #region CRUD */
    public function index(IndexRoleRequest $request)
    {
        $filters = $request->validated();

        $roles = $this->roleService->paginateWithFilters($filters);
        return RoleResource::collection($roles);
    }

    public function show(int $id): JsonResponse
    {
        $role = $this->roleService->findById($id);
        return response()->json(new RoleResource($role));
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $role = $this->roleService->create($data);

        return response()->json([
            'message' => __('messages.roles.created'),
            'data' => new RoleResource($role),
        ], 201);
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $data = $request->validated();
        $role = $this->roleService->update($role->id, $data);
        return response()->json([
            'message' => __('messages.roles.updated'),
            'data' => new RoleResource($role),
        ]);
    }


    public function destroy(Role $role): JsonResponse
    {
        $this->roleService->delete($role->id);
        return response()->json([
            'message' => __('messages.roles.deleted'),
        ]);
    }

    public function restore(Role $role): JsonResponse
    {
        $this->roleService->restore($role->id);
        return response()->json([
            'message' => __('messages.roles.restored'),
        ]);
    }

    public function isUnique(string $roleName): JsonResponse
    {
        $isUnique = $this->roleService->isUnique($roleName);
        return response()->json(['unique' => $isUnique]);
    }

    /* #endregion */


    /* #region Assign Permissions */
    public function assignPermissions(Role $role): JsonResponse
    {
        $permissions = request()->input('permissions', []);
        $this->roleService->assignPermissions($role->id, $permissions);

        return response()->json([
            'message' => __('messages.roles.permissions_assigned'),
        ]);

    }

    public function revokePermissions(Role $role): JsonResponse
    {
        $permissions = request()->input('permissions', []);

        $this->roleService->revokePermissions($role->id, $permissions);

        return response()->json([
            'message' => __('messages.roles.permissions_revoked'),
        ]);

    }
    /* #endregion */

    /* #region Fetch Role Permissions  */
    public function fetchAssignedPermissions(Role $role, IndexRoleRequest $request)
    {
        $permissions = $this->roleService->fetchAssignedPermissions($role->id, $request->validated());
        return PermissionResource::collection($permissions);

    }

    public function fetchUnAssignedPermissions(Role $role): JsonResponse
    {
        $permissions = $this->roleService->fetchUnassignedPermissions($role->id);
        return response()->json([
            'data' => $permissions,
        ]);
    }
    /* #endregion */


    /* #region Assign Users  */
    public function assignUsers(Role $role): JsonResponse
    {
        $users = request()->input('users', []);
        $this->roleService->assignUsers($role->id, $users);

        return response()->json([
            'message' => __('messages.roles.users_assigned'),
        ]);
    }
    public function revokeUsers(Role $role): JsonResponse
    {
        $users = request()->input('users', []);

        $this->roleService->revokeUsers($role->id, $users);

        return response()->json([
            'message' => __('messages.roles.users_revoked'),
        ]);
    }

    /* #endregion */


    /* #region Fetch Role Users   */
    public function fetchAssignedUsers(Role $role, IndexRoleRequest $request)
    {
        $users = $this->roleService->fetchAssignedUsers($role->id, $request->validated());
        return UserResource::collection($users);

    }
    public function fetchUnAssignedUsers(Role $role): JsonResponse
    {
        $users = $this->roleService->fetchUnassignedUsers($role->id);
        return response()->json([
            'data' => $users,
        ]);
    }
    /* #endregion */
}
