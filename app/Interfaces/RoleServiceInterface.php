<?php

namespace App\Interfaces;

use App\Http\Resources\PermissionCollection;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;


interface RoleServiceInterface
{
    /* #region CRUD */
    public function paginateWithFilters(array $filters);

    public function findById(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function restore(int $id);
    public function isUnique(string $name);
    /* #endregion */

    /* #region Assign Permissions */
    public function assignPermissions(int $id, array $permissions);
    public function revokePermissions(int $id, array $permissions);
    /* #endregion */
    /* #region Fetch Role Permissions  */
    public function fetchAssignedPermissions(int $id,array $filters);
    public function fetchUnAssignedPermissions(int $id);
    /* #endregion */
    /* #region Assign Users  */
    public function assignUsers(int $id, array $users);
    public function revokeUsers(int $id, array $users);
    /* #endregion */

    /* #region Fetch Role Users  */
    public function fetchAssignedUsers(int $id,array $filters);
    public function fetchUnAssignedUsers(int $id);
    /* #endregion */

}
