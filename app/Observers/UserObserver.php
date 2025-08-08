<?php

namespace App\Observers;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Log::info('User created: ' . $user->username . ' by ' . auth()->user()->username);
        $user->created_by = auth()->user()->id;
        $user->created_at = now();
        $user->status = UserStatus::PENDING;
        $user->saveQuietly();
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        Log::info('User updated: ' . $user->title . ' by ' . auth()->user()->username);
        $user->updated_by = auth()->user()->id;
        $user->updated_at = now();
        $user->saveQuietly();

    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        Log::info('User deleted: ' . $user->title . ' by ' . auth()->user()->username);
        $user->deleted_by = auth()->user()->id;
        $user->deleted_at = now();
        $user->status = UserStatus::DELETED;
        $user->saveQuietly();

    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        Log::info('User restored: ' . $user->title . ' by ' . auth()->user()->username);
        $user->deleted_by = null;
        $user->deleted_at = null;
        $user->status = UserStatus::PENDING;
        $user->updated_by = auth()->user()->id;
        $user->updated_at = now();
        $user->saveQuietly();

    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        Log::info('User force deleted: ' . $user->title . ' by ' . auth()->user()->username);
    }
}
