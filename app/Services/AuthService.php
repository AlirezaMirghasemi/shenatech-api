<?php // App/Services/AuthService.php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->createUser($data);
        $user->assignRole('Viewer'); // Ensure 'Viewer' role exists
        return $user;
    }

    /**
     * Attempt to authenticate the user using credentials.
     * Regenerates session ID on successful login.
     *
     * @param array $credentials ['email' => string, 'password' => string]
     * @return \Illuminate\Contracts\Auth\Authenticatable The authenticated user.
     * @throws ValidationException If authentication fails.
     */
    public function login(array $credentials): \Illuminate\Contracts\Auth\Authenticatable
    {
        // Use Laravel's built-in attempt method which handles hashing checks
        // The 'web' guard is typically used for session-based authentication
        if (!Auth::guard('web')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        // Regenerate session ID to prevent session fixation attacks
        request()->session()->regenerate(); // Access session via helper or Request instance

        // Return the authenticated user instance
        // Auth::user() or Auth::guard('web')->user() will now return the logged-in user
        return Auth::guard('web')->user();
    }

    /**
     * Log the user out of the application.
     * Invalidates the session and regenerates CSRF token.
     *
     * @param Request $request
     * @return bool Always returns true in this implementation.
     */
    public function logout(Request $request): bool
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return true;
    }
}
