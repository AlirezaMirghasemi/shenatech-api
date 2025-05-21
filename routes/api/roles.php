<?php

use App\Http\Controllers\Api\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function () {
    Route::prefix('roles')->name('roles.')->middleware('permission:view roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');
        Route::get('/role-name-is-unique/{roleName}', [RoleController::class, 'isUnique'])->name('isUnique');
        Route::get('/{role}/permissions', [RoleController::class, 'getRolePermissions'])
            ->middleware('permission:view permissions')
            ->name('role.permissions');
        Route::get('/{role}/users', [RoleController::class, 'getRoleUsers'])
            ->middleware('permission:view users')
            ->name('role.users');
        Route::get('/{role}/not-permissions', [RoleController::class, 'getNotRolePermissions'])
            ->middleware('permission:view permissions')
            ->name('role.notPermissions');
        Route::middleware('permission:manage roles')->group(function () {
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::put('/{role}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
            Route::post('/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('assignPermissions');
            Route::delete('/{role}/revoke-permissions', [RoleController::class, 'revokePermissions'])->name('revokePermissions');
        });
    });
});
