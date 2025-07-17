<?php

namespace App\Observers;

use App\Models\Slug;

class SlugObserver
{
    /**
     * Handle the Slug "created" event.
     */
    public function created(Slug $slug): void
    {
        //
    }

    /**
     * Handle the Slug "updated" event.
     */
    public function updated(Slug $slug): void
    {
        //
    }

    /**
     * Handle the Slug "deleted" event.
     */
    public function deleted(Slug $slug): void
    {
        //
    }

    /**
     * Handle the Slug "restored" event.
     */
    public function restored(Slug $slug): void
    {
        //
    }

    /**
     * Handle the Slug "force deleted" event.
     */
    public function forceDeleted(Slug $slug): void
    {
        //
    }
}
