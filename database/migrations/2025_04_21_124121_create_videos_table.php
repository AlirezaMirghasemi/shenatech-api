<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ContentStatus; // Import Enum

return new class extends Migration {
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id(); // Added Auto Increment (was missing in SQL)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('slug_id')->constrained('slugs')->onDelete('restrict');
            $table->foreignId('poster_id')->nullable()->constrained('images')->onDelete('set null');
            $table->string('title', 255); // Increased length
            $table->text('content')->nullable(); // Changed to TEXT, nullable
            $table->string('status')->default(ContentStatus::PENDING->value); // Use Enum default
            $table->string('url', 2048); // Increased length for URL
            $table->timestamps();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
            ;

            $table->softDeletes();

            $table->foreignId('deleted_by')
                ->nullable()
                ->constrained('users')
            ;
            $table->foreignId('restored_by')
                ->nullable()
                ->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
