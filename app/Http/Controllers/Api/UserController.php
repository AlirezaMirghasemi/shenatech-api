<?php

namespace App\Http\Controllers\Api;

use App\Enums\ImageType;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Interfaces\UserServiceInterface; // Inject Service Interface
use App\Http\Resources\UserResource;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UploadProfileImageRequest;
use App\Http\Requests\User\AssignRolesRequest;
use App\Models\Image;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; // Needed for type hinting if not using specific requests sometimes
use Spatie\Permission\Contracts\Role;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        // Pass query parameters for potential filtering in service
        $users = $this->userService->getAllUsers($request->query());
        return UserResource::collection($users)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        unset($validated['password_confirmation']);

        $user = $this->userService->createUser($validated);

        if ($request->hasFile('profile_image')) {
            $this->userService->uploadProfileImage($user->id, $request->file('profile_image'));
            $user->refresh();
        }
        return (new UserResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED); // 201
    }

    /**
     * Display the specified resource.
     * We use route model binding here for simplicity,
     * but the service layer also handles finding the user.
     */
    public function show(User $user): JsonResponse // Using Route Model Binding
    {
        // Service layer handles authorization and loading relations if needed via getUserById
        $loadedUser = $this->userService->getUserById($user->id); // Re-fetch via service for consistency if needed
        return (new UserResource($loadedUser))->response();
    }

    /**
     * Update the specified resource in storage.
     * Using route model binding here.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->userService->updateUser(
            $user->id,
            $request->validated(),
            $request->file('profile_image') // ارسال فایل به سرویس
        );

        return (new UserResource($updatedUser))->response();

    }

    /**
     * Remove the specified resource from storage.
     * Using route model binding here.
     */
    public function destroy(User $user ,Request $request): JsonResponse
    {
        $removeProfilePicture = $request->collect('removeProfilePicture');
        $this->userService->deleteUser($user->id, $removeProfilePicture[0]);
        return response()->json(null, Response::HTTP_NO_CONTENT); // 204
    }

    /**
     * Upload profile image for the specified user.
     * Using route model binding here.
     */
    public function uploadProfileImage(UploadProfileImageRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->userService->uploadProfileImage($user->id, $request->file('profile_image'));
        return (new UserResource($updatedUser))->response();
    }

    /**
     * Assign roles to the specified user.
     * Using route model binding here.
     */
    public function assignRoles(AssignRolesRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->userService->assignRolesToUser($user->id, $request->validated('roles'));
        return (new UserResource($updatedUser))->response();
    }
    public function isUnique(Request $request): bool
    {
        $fieldName = $request->query('fieldName');
        $fieldValue = $request->query('fieldValue');
        $isUnique = $this->userService->isUnique($fieldName, $fieldValue);
        return $isUnique;
    }
    public function updateStatus(Request $request, User $user): JsonResponse
    {
        $status = $request->input('status');
        $updatedUser = $this->userService->updateUser($user->id, ['status' => $status]);
        return (new UserResource($updatedUser))->response();
    }
    public function fetchUnAssignedRoleUsers(int $roleId, RoleService $roleService): array
    {
        $role = $roleService->findRoleById($roleId);
        $unassignedUsers = $this->userService->getUnAssignedRoleUsers($role);
        return UserResource::collection($unassignedUsers)->response()->getData(true);
    }
}
