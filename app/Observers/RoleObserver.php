<?php

namespace App\Observers;

use App\Enums\CommonStatus;
use App\Models\Role;
use Illuminate\Support\Facades\Log;


class RoleObserver
{
    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        Log::info('Role created: ' . $role->name . ' by ' . auth()->user()->username);
        $role->created_by = auth()->user()->id;
        $role->created_at = now();
        $role->status = CommonStatus::ACTIVE;
        $role->saveQuietly();


    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        Log::info('Role updated: ' . $role->name . ' by ' . auth()->user()->username);
        $role->updated_by = auth()->user()->id;
        $role->updated_at = now();
        $role->saveQuietly();

    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        Log::info('Role deleted: ' . $role->name . ' by ' . auth()->user()->username);
        $role->deleted_by = auth()->user()->id;
        $role->deleted_at = now();
        $role->status = CommonStatus::DELETED;
        $role->saveQuietly();

    }

    /**
     * Handle the Role "restored" event.
     */
    public function restored(Role $role): void
    {
        Log::info('Role restored: ' . $role->name . ' by ' . auth()->user()->username);
        $role->deleted_by = null;
        $role->deleted_at = null;
        $role->status = CommonStatus::ACTIVE;
        $role->updated_by = auth()->user()->id;
        $role->updated_at = now();
        $role->saveQuietly();

    }

    /**
     * Handle the Role "force deleted" event.
     */
    public function forceDeleted(Role $role): void
    {
        Log::info('Role force deleted: ' . $role->name . ' by ' . auth()->user()->username);
    }
}
