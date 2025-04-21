<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reference_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            // Nullable foreign keys for the item being tagged
            $table->foreignId('article_id')->nullable()->constrained('articles')->onDelete('cascade');
            $table->foreignId('video_id')->nullable()->constrained('videos')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade');
            $table->timestamps(); // Keep timestamps as requested

            // Optional: Add unique constraint to prevent duplicate tags on the same item type
            // Note: Handling uniqueness across multiple nullable columns needs careful consideration.
            // It might be better to enforce this logic in the application layer (Service).
            // Example (might not work correctly with multiple nulls depending on DB):
            $table->unique(['tag_id', 'article_id']);
            $table->unique(['tag_id', 'video_id']);
            $table->unique(['tag_id', 'event_id']);

            // Add indexes if not automatically added by constrained() or if needed for specific queries
            $table->index('article_id');
            $table->index('video_id');
            $table->index('event_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reference_tags');
    }
};
