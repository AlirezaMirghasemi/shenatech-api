<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Contracts\Services\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        Gate::authorize('manage users');
        $users = $this->userService->getAllUsers();
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return new UserResource($user);
    }

    public function show(int $id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $user = $this->userService->updateUser($id, $request->validated());
        return new UserResource($user);
    }

    public function destroy(int $id)
    {
        Gate::authorize('manage users');
        if ($this->userService->deleteUser($id)) {
            return response()->json(['message' => 'User deleted']);
        }
        return response()->json(['error' => 'User not found'], 404);
    }

    public function uploadProfileImage(Request $request, int $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        Gate::authorize('edit own profile', $this->userService->getUserById($id));
        $user = $this->userService->uploadProfileImage($id, $request->file('image'));
        return new UserResource($user);
    }
}
