<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ContentStatus; // Import Enum

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('slug_id')->constrained('slugs')->onDelete('restrict');
            $table->foreignId('poster_id')->nullable()->constrained('images')->onDelete('set null');
            $table->string('title', 255);
            $table->text('content');
            $table->string('status')->default(ContentStatus::PENDING->value); // Use Enum default
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
