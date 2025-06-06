<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());
        return response()->json([
            'message' => 'User registered successfully. Please login.',
            'data'    => new UserResource($user)
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authService->login($request->validated());
        return response()->json([
            'data' => new UserResource($user)
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request);

        // منقضی‌سازی کوکی‌های HttpOnly
        $cookie = Cookie::forget($request->session()->getName());


        return response()
            ->json(['message' => 'Logged out successfully'], 200)
            ->withCookie($cookie);
    }


    public function user(): JsonResponse
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return response()->json([
            'data' => $user
                ? new UserResource($user->load('profileImage'))
                : null
        ], $user ? 200 : 401);
    }
}
