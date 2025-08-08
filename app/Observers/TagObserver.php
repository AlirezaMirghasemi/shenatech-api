<?php

namespace App\Observers;

use App\Enums\CommonStatus;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;

class TagObserver
{
    /**
     * Handle the Tag "created" event.
     */
    public function created(Tag $tag): void
    {
        Log::info('Tag created: ' . $tag->title . ' by ' . auth()->user()->username);
        $tag->created_by = auth()->user()->id;
        $tag->created_at = now();
        $tag->status = CommonStatus::ACTIVE;
        $tag->saveQuietly();
    }

    /**
     * Handle the Tag "updated" event.
     */
    public function updated(Tag $tag): void
    {
        Log::info('Tag updated: ' . $tag->title . ' by ' . auth()->user()->username);
        $tag->updated_by = auth()->user()->id;
        $tag->updated_at = now();
        $tag->saveQuietly();

    }

    /**
     * Handle the Tag "deleted" event.
     */
    public function deleted(Tag $tag): void
    {
        Log::info('Tag deleted: ' . $tag->title . ' by ' . auth()->user()->username);
        $tag->deleted_by = auth()->user()->id;
        $tag->deleted_at = now();
        $tag->status = CommonStatus::DELETED;
        $tag->saveQuietly();

    }

    /**
     * Handle the Tag "restored" event.
     */
    public function restored(Tag $tag): void
    {
        Log::info('Tag restored: ' . $tag->title . ' by ' . auth()->user()->username);
        $tag->deleted_by = null;
        $tag->deleted_at = null;
        $tag->status = CommonStatus::ACTIVE;
        $tag->updated_by = auth()->user()->id;
        $tag->updated_at = now();
        $tag->saveQuietly();

    }

    /**
     * Handle the Tag "force deleted" event.
     */
    public function forceDeleted(Tag $tag): void
    {
        Log::info('Tag force deleted: ' . $tag->title . ' by ' . auth()->user()->username);
    }
}
