<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Contracts\Services\RoleServiceInterface;
use Exception;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        Gate::authorize('manage roles');
        $roles = $this->roleService->getAllRoles();
        return RoleResource::collection($roles);
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->roleService->createRole($request->validated());
        if (isset($request["permissions"])) {
            $role->syncPermissions($request["permissions"]);
        }
        return new RoleResource($role);
    }

    public function show(int $id)
    {
        Gate::authorize('manage roles');
        $role = $this->roleService->getRoleById($id);
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }
        return new RoleResource($role);
    }

    public function update(UpdateRoleRequest $request, int $id)
    {
        $role = $this->roleService->updateRole($id, $request->validated());
        if (isset($request["permissions"])) {
            $role->syncPermissions($request["permissions"]);
        }
        return new RoleResource($role);
    }

    public function destroy(int $id)
    {
        Gate::authorize('manage roles');
        if ($this->roleService->deleteRole($id)) {
            return response()->json(['message' => 'Role deleted']);
        }
        return response()->json(['error' => 'Role not found'], 404);
    }
}
