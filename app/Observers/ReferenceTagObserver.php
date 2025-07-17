<?php

namespace App\Observers;

use App\Models\ReferenceTag;

class ReferenceTagObserver
{
    /**
     * Handle the ReferenceTag "created" event.
     */
    public function created(ReferenceTag $referenceTag): void
    {
        //
    }

    /**
     * Handle the ReferenceTag "updated" event.
     */
    public function updated(ReferenceTag $referenceTag): void
    {
        //
    }

    /**
     * Handle the ReferenceTag "deleted" event.
     */
    public function deleted(ReferenceTag $referenceTag): void
    {
        //
    }

    /**
     * Handle the ReferenceTag "restored" event.
     */
    public function restored(ReferenceTag $referenceTag): void
    {
        //
    }

    /**
     * Handle the ReferenceTag "force deleted" event.
     */
    public function forceDeleted(ReferenceTag $referenceTag): void
    {
        //
    }
}
