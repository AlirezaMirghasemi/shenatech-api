<?php

use App\Http\Controllers\Api\RoleController;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('roles')
    ->name('roles.')
    ->group(function () {
        /* #region CRUD */
        Route::get('/', [RoleController::class, 'index'])
            ->can('viewAny', Role::class)
            ->name('index');

        Route::get('/{role}', [RoleController::class, 'show'])
            ->can('view', 'role')
            ->name('show');

        Route::post('/', [RoleController::class, 'store'])
            ->can('create', Role::class)
            ->name('store');

        Route::put('/{role}', [RoleController::class, 'update'])
            ->can('update', 'role')
            ->name('update');


        Route::delete('/{role}', [RoleController::class, 'destroy'])
            ->can('delete', 'role')
            ->name('destroy');


        Route::put('/restore/{roles}', [RoleController::class, 'restore'])
            ->can('update', 'role')
            ->name('restore');


        Route::get('/is_unique/{roleName}', [RoleController::class, 'isUnique'])
            ->can('viewAny', Role::class)
            ->name('isUnique');
        /* #endregion */



        /* #region Assign Permissions */
        Route::post('/{role}/assign_permissions', [RoleController::class, 'assignPermissions'])
            ->can('assignPermissions', 'role')
            ->name('assignPermissions');


        Route::delete('/{role}/revoke_permissions', [RoleController::class, 'revokePermissions'])
            ->can('assignPermissions', 'role')
            ->name('revokePermissions');

        /* #endregion */

        /* #region Fetch Role Permissions  */

        Route::get('/{role}/assigned_permissions', [RoleController::class, 'fetchAssignedPermissions'])
            ->can('viewAny', Permission::class)
            ->name('fetchAssignedPermissions');


        Route::get('/{role}/unassigned_permissions', [RoleController::class, 'fetchUnAssignedPermissions'])
            ->can('viewAny', Permission::class)
            ->name('fetchUnAssignedPermissions');

        /* #endregion */

        /* #region Assign Users */
        Route::put('/{role}/assign_users', [RoleController::class, 'assignUsers'])
            ->can('assignUsers', 'role')
            ->name('assignUsers');
        Route::delete('/{role}/revoke_users', [RoleController::class, 'revokeUsers'])
            ->can('assignUsers', 'role')
            ->name('revokeUsers');
        /* #endregion */

        /* #region Fetch Role Users */
        Route::get('/{role}/assigned_users', [RoleController::class, 'fetchAssignedUsers'])
            ->can('viewAny', User::class)
            ->name('fetchAssignedUsers');
        Route::get('/{role}/unassigned_users', [RoleController::class, 'fetchUnAssignedUsers'])
            ->can('viewAny', User::class)
            ->name('fetchUnAssignedUsers');
        /* #endregion */

    });
