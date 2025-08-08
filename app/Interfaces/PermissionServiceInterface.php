<?php

namespace App\Interfaces;

use App\Models\Permission;
use Illuminate\Support\Collection as SupportCollection;

interface PermissionServiceInterface
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

    /* #region Assign Roles */
    public function assignRoles(int $id, array $roles);
    public function revokeRoles(int $id, array $roles);
    /* #endregion */
    /* #region Fetch Permission Roles  */
    public function fetchAssignedRoles(int $id, array $filters);
    public function fetchUnAssignedRoles(int $id);
    /* #endregion */
    /* #region Fetch Permission Users  */
    public function fetchAssignedUsers(int $id, array $filters);
    public function fetchUnAssignedUsers(int $id);
    /* #endregion */



}
