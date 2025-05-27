<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\UserGender; // Import Enum
use App\Enums\UserStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable(); // Standard Laravel field
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable(); // Corrected casing
            $table->string('password'); // Laravel handles length & hashing
            $table->text('bio')->nullable();
            $table->string('gender')->default(UserGender::NOT_SPECIFIED->value)->nullable(); // Use Enum default
            $table->string('status')->default(UserStatus::PENDING->value);
            $table->foreignId('image_id')->nullable()->constrained('images')->onDelete('set null'); // Foreign key after images table
            $table->string('mobile', 20)->unique()->nullable(); // Changed to VARCHAR, nullable
            $table->timestamp('mobile_verified_at')->nullable();
            $table->rememberToken(); // Standard Laravel field
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    public function down(): void
    {
        // Drop foreign key constraint first if necessary (Laravel handles this automatically if using constrained())
        Schema::dropIfExists('users');
    }
};
