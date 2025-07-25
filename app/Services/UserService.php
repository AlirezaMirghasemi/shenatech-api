<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Gate; // For authorization
use Illuminate\Support\Facades\Log; // For logging errors
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(int $page = 1, int $perPage = 10, string $search = null)
    {

        $filters["per_page"] = $perPage;
        $filters["search"] = $search;
        $filters["page"] = $page;
        // Authorization Check
        if (Gate::denies('view users')) {
            throw new AuthorizationException('You do not have permission to view users.');
        }
        if ($search != "") {
            $users = $this->userRepository->getAllUsers($filters, ['roles', 'profileImage'])
                ->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('first_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%")
                ->orWhere('mobile', 'like', "%$search%");
        } else {
            $users = $this->userRepository->getAllUsers($filters, ['roles', 'profileImage']);
        }

        return $users->paginate($perPage);
    }





    public function getUserById(int $id): ?User
    {
        $user = $this->userRepository->findUserById($id, ['roles', 'permissions', 'profileImage']);
        if (!$user) {
            throw new NotFoundHttpException("User with ID {$id} not found.");
        }

        // Authorization Check (View own profile or manage users)
        $currentUser = auth()->user(); // Get the currently authenticated user
        if ($currentUser->id !== $user->id && Gate::denies('view users')) {
            throw new AuthorizationException('You do not have permission to view this user.');
        }

        return $user;
    }

    public function createUser(array $userData): User
    {
        // Authorization Check
        if (Gate::denies('manage users')) {
            throw new AuthorizationException('You do not have permission to create users.');
        }
        $userData['password'] = bcrypt($userData['password']);
        // Note: Password hashing is handled in Repository or RegisterRequest/AuthService
        $user = $this->userRepository->createUser($userData);

        // Assign roles if provided and user has permission
        if (isset($userData['roles']) && auth()->user()->can('assign roles')) {
            $this->assignRolesToUser($user->id, $userData['roles']);
        } else {
            $user->assignRole('Viewer');
        }

        return $user->load('roles', 'profileImage');
    }

    public function updateUser(int $id, array $userData, ?UploadedFile $image = null): User
    {
        $user = $this->getUserById($id); // Handles NotFoundException

        // Authorization Check (Edit own profile or manage users)
        $currentUser = auth()->user();
        if ($currentUser->id !== $user->id && Gate::denies('manage users') && Gate::denies('edit own profile', $user)) { // Check both general and specific permission
            throw new AuthorizationException('You do not have permission to update this user.');
        }

        // Prevent users from escalating privileges unless they have 'assign roles' permission
        if (isset($userData['roles']) && Gate::denies('assign roles')) {
            // Log the attempt and remove roles from data to prevent update
            Log::warning('User tried to update roles without permission.', ['updater_id' => $currentUser->id, 'target_user_id' => $id]);
            unset($userData['roles']);
        }
        if ($image) {
            $this->uploadProfileImage($user->id, $image);
        }
        $this->userRepository->updateUser($user, $userData);

        // Handle role assignment separately after user update if roles are provided and user has permission
        if (isset($userData['roles']) && Gate::allows('assign roles')) {
            $this->assignRolesToUser($user->id, $userData['roles']);
        }

        return $user->fresh(['roles', 'permissions', 'profileImage']); // Refresh model and load relations
    }

    public function deleteUser(int $id, array $options): bool
    {
        $user = $this->getUserById($id); // Handles NotFoundException

        // Authorization Check
        if (Gate::denies('manage users')) {
            throw new AuthorizationException('You do not have permission to delete users.');
        }

        // Prevent deleting self? (Optional business rule)
        if (auth()->id() === $id) {
            throw new AuthorizationException('You cannot delete your own account.');
        }
        if (!(auth()->user()->roles()->where('name', 'Admin')->exists())) {
            if ($user->roles()->where('name', 'Admin')->exists()) {
                throw new AuthorizationException("You cannot delete the {$user->role->name} role.");
            }
        }
        return $this->userRepository->deleteUser($user, $options);
    }

    public function uploadProfileImage(int $userId, UploadedFile $image): User
    {
        $user = $this->getUserById($userId); // Handles NotFoundException

        // Authorization Check (Upload own profile or manage users)
        $currentUser = auth()->user();
        if ($currentUser->id !== $user->id && Gate::denies('manage users') && Gate::denies('edit own profile', $user)) {
            throw new AuthorizationException('You do not have permission to upload profile image for this user.');
        }

        $path = $this->userRepository->updateUserProfileImage($user, $image);

        if (!$path) {
            // Handle upload failure (log error, throw exception, etc.)
            Log::error("Failed to upload profile image for user ID: {$userId}");
            throw new \Exception("Profile image upload failed."); // Or a more specific exception
        }

        return $user->fresh('profileImage'); // Return user with updated image relation
    }

    public function assignRolesToUser(int $userId, array $roles): User
    {
        $user = $this->getUserById($userId);

        // Authorization Check
        if (Gate::denies('assign roles')) {
            throw new AuthorizationException('You do not have permission to assign roles.');
        }

        // Validate that roles exist (optional but recommended)
        $validRoles = Role::whereIn('name', $roles)->pluck('name')->toArray();
        if (count($validRoles) !== count($roles)) {
            $invalidRoles = array_diff($roles, $validRoles);
            throw ValidationException::withMessages([
                'roles' => ['Invalid roles provided: ' . implode(', ', $invalidRoles)],
            ]);
        }

        // Use syncRoles to assign the exact roles provided, removing any others
        $user->syncRoles($validRoles);

        return $user->load('roles', 'permissions'); // Load relations for response
    }
    public function isUnique(string $fieldName, string $fieldValue): bool
    {
        return $this->userRepository->isUnique($fieldName, $fieldValue);
    }
    public function getUnAssignedRoleUsers(Role $role): SupportCollection
    {
        return $this->userRepository->getUnAssignedRoleUsers($role);
    }
}
