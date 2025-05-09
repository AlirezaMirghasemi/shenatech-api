<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest; // Store Request
use App\Http\Requests\Role\UpdateRoleRequest; // Update Request
use App\Http\Requests\Role\AssignPermissionsRequest; // Assign Permissions Request
use App\Http\Resources\RoleResource;
use App\Interfaces\RoleServiceInterface;
use Spatie\Permission\Models\Role; // For Route Model Binding
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; // Needed for type hinting sometimes
use Symfony\Component\HttpFoundation\Response; // For status codes


class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
        // Authorization is primarily handled within the Service layer methods
        // but you can add middleware here for clarity or pre-checks if preferred.
        // $this->middleware('permission:view roles')->only(['index', 'show']);
        // $this->middleware('permission:manage roles')->only(['store', 'update', 'destroy', 'assignPermissions']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10); // دریافت تعداد آیتم در هر صفحه
        $roles = $this->roleService->getAllRoles($perPage);
        return RoleResource::collection($roles)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->createRole($request->validated()); // Service handles authorization and permission assignment
        return (new RoleResource($role))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED); // 201
    }

    /**
     * Display the specified resource.
     * Using route model binding.
     */
    public function show(Role $role): JsonResponse // Using Route Model Binding
    {
        // Service layer handles authorization and loading relations if needed via findRoleById
        $loadedRole = $this->roleService->findRoleById($role->id); // Re-fetch via service for consistency if needed
        return (new RoleResource($loadedRole))->response();
    }

    /**
     * Update the specified resource in storage.
     * Using route model binding.
     */
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $updatedRole = $this->roleService->updateRole($role->id, $request->validated()); // Service handles authorization and permission assignment
        return (new RoleResource($updatedRole))->response();
    }

    /**
     * Remove the specified resource from storage.
     * Using route model binding.
     */
    public function destroy(Role $role): JsonResponse
    {
        $this->roleService->deleteRole($role->id); // Service handles authorization and constraints
        return response()->json(null, Response::HTTP_NO_CONTENT); // 204
    }

    /**
     * Assign permissions to the specified role.
     * Using route model binding.
     */
    public function assignPermissions(AssignPermissionsRequest $request, Role $role): JsonResponse
    {
        $updatedRole = $this->roleService->assignPermissionsToRole($role->id, $request->validated('permissions')); // Service handles authorization and validation
        return (new RoleResource($updatedRole))->response();
    }

    public function getRolePermissions(Role $role, Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10); // دریافت تعداد آیتم در هر صفحه

        $permissionsResponse = $this->roleService->getRolePermissions($role, $perPage);
        return $permissionsResponse;
    }
    public function getRoleUsers(Role $role, Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10); // دریافت تعداد آیتم در هر صفحه

        $usersResponse = $this->roleService->getRoleUsers($role, $perPage);
        return $usersResponse;
    }

}
