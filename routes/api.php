<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
// Import other controllers as you implement them (e.g., ArticleController)
// use App\Http\Controllers\Api\ArticleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (No authentication required)
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// Protected routes (Require authentication via Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Authentication - Protected Endpoints
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/user', [AuthController::class, 'user'])->name('user'); // Get authenticated user info
    });


    // --- User Management Routes ---
    Route::prefix('users')->name('users.')->group(function () {
        // Index and Show can be viewed by anyone with 'view users' permission
        Route::get('/', [UserController::class, 'index'])->middleware('permission:view users')->name('index');
        Route::get('/{user}', [UserController::class, 'show'])->middleware('permission:view users')->name('show');

        // Store, Update, Destroy require 'manage users' or 'edit own profile' for update
        Route::post('/', [UserController::class, 'store'])->middleware('permission:manage users')->name('store');
        // Using PUT for update as per RESTful conventions
        Route::put('/{user}', [UserController::class, 'update'])->middleware('permission:manage users|edit own profile')->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('permission:manage users')->name('destroy');

        // Specific user actions
        Route::post('/{user}/upload-profile-image', [UserController::class, 'uploadProfileImage'])
            ->middleware('permission:edit own profile|manage users')
            ->name('uploadImage');

        Route::post('/{user}/assign-roles', [UserController::class, 'assignRoles'])
            ->middleware('permission:assign roles')
            ->name('assignRoles');
    });


    // --- Role Management Routes ---
    Route::prefix('roles')->name('roles.')->middleware('permission:view roles')->group(function () {
        // Index and Show only require 'view roles'
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');

        // Store, Update, Destroy, AssignPermissions require 'manage roles'
        Route::middleware('permission:manage roles')->group(function () {
            Route::post('/', [RoleController::class, 'store'])->name('store');
            // Using PUT for update
            Route::put('/{role}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
            Route::post('/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('assignPermissions');
        });
    });


    // --- Permission Management Routes ---
    // Permissions are usually just listed, requires 'view permissions'
    Route::get('/permissions', [PermissionController::class, 'index'])
        ->middleware('permission:view permissions')
        ->name('permissions.index');


    // --- Other Resource Routes (Example: Articles) ---
    // Group routes for other resources similarly.
    // Route::prefix('articles')->name('articles.')->group(function () {
    //     Route::get('/', [ArticleController::class, 'index'])->middleware('permission:view articles')->name('index');
    //     Route::get('/{article}', [ArticleController::class, 'show'])->middleware('permission:view articles')->name('show');
    //
    //     Route::middleware('permission:create articles')->group(function () {
    //         Route::post('/', [ArticleController::class, 'store'])->name('store');
    //     });
    //
    //     Route::middleware('permission:edit own articles|manage articles')->group(function () {
    //          // Using PUT for update
    //          Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
    //     });
    //
    //     Route::middleware('permission:delete own articles|manage articles')->group(function () {
    //          Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
    //     });
    //
    //     // Example custom action with specific permission
    //     Route::post('/{article}/publish', [ArticleController::class, 'publish'])
    //           ->middleware('permission:publish articles')
    //           ->name('publish');
    // });


    // Add route groups for other resources (Events, Videos, Comments, Tags, Slugs) similarly
    // Follow RESTful conventions (GET for index/show, POST for store, PUT/PATCH for update, DELETE for destroy)
    // Apply appropriate permissions using middleware on groups or individual routes.

});

// Fallback route for 404 API errors - Keep this at the very end
Route::fallback(function () {
    return response()->json(['message' => 'Not Found.'], 404);
});
