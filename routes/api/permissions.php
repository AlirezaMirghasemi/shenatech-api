<?php

use App\Http\Controllers\Api\PermissionController;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('permissions')
    ->name('permissions.')
    ->group(function () {
        /* #region CRUD */
        Route::get('/', [PermissionController::class, 'index'])
            ->can('viewAny', Permission::class)
            ->name('index');

        Route::get('/{permission}', [PermissionController::class, 'show'])
            ->can('view', 'permission')
            ->name('show');

        Route::post('/', [PermissionController::class, 'store'])
            ->can('create', Permission::class)
            ->name('store');

        Route::put('/{permission}', [PermissionController::class, 'update'])
            ->can('update', 'permission')
            ->name('update');


        Route::delete('/{permission}', [PermissionController::class, 'destroy'])
            ->can('delete', 'permission')
            ->name('destroy');


        Route::put('/restore/{permission}', [PermissionController::class, 'restore'])
            ->can('update', 'permission')
            ->name('restore');


        Route::get('/is_unique/{permissionName}', [PermissionController::class, 'isUnique'])
            ->can('viewAny', Permission::class)
            ->name('isUnique');
        /* #endregion */

        /* #region Assign Roles */
        Route::post('/{permission}/assign_roles', [PermissionController::class, 'assignRoles'])
            ->can('assignRoles', 'permission')
            ->name('assignRoles');


        Route::delete('/{permission}/revoke_permissions', [PermissionController::class, 'revokeRoles'])
            ->can('assignRoles', 'permission')
            ->name('revokeRoles');

        /* #endregion */

        /* #region Fetch Permission Roles  */
        Route::get('/{permission}/assigned_roles', [PermissionController::class, 'fetchAssignedRoles'])
            ->can('viewAny', Role::class)
            ->name('fetchAssignedRoles');


        Route::get('/{permission}/unassigned_roles', [PermissionController::class, 'fetchUnAssignedRoles'])
            ->can('viewAny', Role::class)
            ->name('fetchUnAssignedRoles');
        /* #endregion */

        /* #region Fetch Permission Users */
        Route::get('/{permission}/assigned_users', [PermissionController::class, 'fetchAssignedUsers'])
            ->can('viewAny', User::class)
            ->name('fetchAssignedUsers');

        Route::get('/{permission}/unassigned_users', [PermissionController::class, 'fetchUnAssignedUsers'])
            ->can('viewAny', User::class)
            ->name('fetchUnAssignedUsers');
        /* #endregion */

    });
