<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CommentStatus; // Import Enum

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Nullable foreign keys for the item being commented on
            $table->foreignId('article_id')->nullable()->constrained('articles')->onDelete('cascade');
            $table->foreignId('video_id')->nullable()->constrained('videos')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade');
            // Self-referencing key for replies (must be nullable)
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // Corrected FK definition
            $table->text('content');
            $table->string('status')->default(CommentStatus::PENDING->value); // Use Enum default
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for faster lookups on foreign keys used in queries
            $table->index('article_id'); // Constrained() adds index automatically
            $table->index('video_id');
            $table->index('event_id');
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
