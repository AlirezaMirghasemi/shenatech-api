<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () { });
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->middleware('permission:view users')->name('index');
        Route::post('/restores', [UserController::class, 'restores'])->middleware('permission:manage users')->name('restores');
        Route::post('/', action: [UserController::class, 'store'])->middleware('permission:manage users')->name('store');
        Route::get('field-is-unique', [UserController::class, 'isUnique'])->middleware('permission:view users')->name('isUnique');

        Route::put('/{user}/status', [UserController::class, 'updateStatus'])->middleware('permission:manage users')->name('updateStatus');
        Route::get('/{user}', [UserController::class, 'show'])->middleware('permission:view users')->name('show');
        Route::post('/{user}', [UserController::class, 'update'])->middleware('permission:manage users|edit own profile')->name('update');
        Route::post('/delete/{user}', [UserController::class, 'destroy'])->middleware('permission:manage users')->name('destroy');

        Route::post('/{user}/upload-profile-image', [UserController::class, 'uploadProfileImage'])
            ->middleware('permission:edit own profile|manage users')
            ->name('uploadImage');
        Route::post('/{user}/assign-roles', [UserController::class, 'assignRoles'])
            ->middleware('permission:assign roles')
            ->name('assignRoles');
        Route::get('/{role}/roles/unassigned', [UserController::class, 'fetchUnAssignedRoleUsers'])->middleware('permission:view users')->name('fetchUnAssignedRoleUsers');

    });
});
