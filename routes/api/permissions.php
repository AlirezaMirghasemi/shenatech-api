<?php

use App\Http\Controllers\Api\PermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function () {
    Route::prefix('permissions')->name('permissions.')->middleware('permission:view permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/{permission}', [PermissionController::class, 'show'])->name('show');
        Route::get('/permission-name-is-unique/{permissionName}', [PermissionController::class, 'isUnique'])->name('isUnique');
        Route::get('/{permission}/roles', [PermissionController::class, 'getPermissionRoles'])->name('getPermissionRoles');
        Route::get('/{permission}/users',[PermissionController::class,'getPermissionUsers'])->name('getPermissionUsers');
        Route::middleware('permission:manage permissions')->group(function () {
            Route::post('/', [PermissionController::class, 'store'])->name('store');
            Route::delete('/', [PermissionController::class, 'destroy'])->name('destroy');
            Route::delete('/{permission}/revoke-roles', [PermissionController::class, 'revokeRoles'])->name('revokeRoles');
        });
    });
});
