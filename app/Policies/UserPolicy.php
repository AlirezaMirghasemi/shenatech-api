<?php
namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view users');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo('view users');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage users');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('manage users');
    }

    public function delete(User $user, User $model): bool
    {
        if ($model->hasRole('Admin')) {
            return false;
        }

        return $user->hasPermissionTo('manage users');
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasPermissionTo('manage users');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('Admin') && $user->hasPermissionTo('manage users');
    }
}
