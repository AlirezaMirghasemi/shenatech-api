<?php

namespace App\Observers;

use App\Enums\CommonStatus;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;

class PermissionObserver
{
    /**
     * Handle the Permission "created" event.
     */
    public function created(Permission $permission): void
    {
        Log::info('Permission created: ' . $permission->name . ' by ' . auth()->user()->username);
        $permission->created_by = auth()->user()->id;
        $permission->created_at = now();
        $permission->status = CommonStatus::ACTIVE;
        $permission->saveQuietly();
    }

    /**
     * Handle the Permission "updated" event.
     */
    public function updated(Permission $permission): void
    {
        Log::info('Permission updated: ' . $permission->name . ' by ' . auth()->user()->username);
        $permission->updated_by = auth()->user()->id;
        $permission->updated_at = now();
        $permission->saveQuietly();

    }

    /**
     * Handle the Permission "deleted" event.
     */
    public function deleted(Permission $permission): void
    {
        Log::info('Permission deleted: ' . $permission->name . ' by ' . auth()->user()->username);
        $permission->deleted_by = auth()->user()->id;
        $permission->deleted_at = now();
        $permission->status = CommonStatus::DELETED;
        $permission->saveQuietly();

    }

    /**
     * Handle the Permission "restored" event.
     */
    public function restored(Permission $permission): void
    {
        Log::info('Permission restored: ' . $permission->name . ' by ' . auth()->user()->username);
        $permission->deleted_by = null;
        $permission->deleted_at = null;
        $permission->status = CommonStatus::ACTIVE;
        $permission->updated_by = auth()->user()->id;
        $permission->updated_at = now();
        $permission->saveQuietly();

    }

    /**
     * Handle the Permission "force deleted" event.
     */
    public function forceDeleted(Permission $permission): void
    {
        Log::info('Permission force deleted: ' . $permission->name . ' by ' . auth()->user()->username);
    }
}
