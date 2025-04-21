<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ImageType; // Import Enum

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id(); // BigInt Unsigned Auto Increment Primary Key
            $table->string('title')->nullable();
            $table->string('type')->default(ImageType::CONTENT->value); // Use Enum default
            $table->string('path'); // Relative path to the image file
            $table->string('disk')->default('public'); // Storage disk used
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size')->nullable(); // File size in bytes
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
