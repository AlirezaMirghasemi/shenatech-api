<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\UserGender;
use App\Enums\UserStatus;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('password');
            $table->text('bio')->nullable();
            $table->string('gender')->default(UserGender::NOT_SPECIFIED->value)->nullable();
            $table->string('status')->default(UserStatus::PENDING->value);

            // تغییر: استفاده از unsignedBigInteger به جای foreignId
            $table->unsignedBigInteger('image_id')->nullable();

            $table->string('mobile', 20)->unique()->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // تغییر: استفاده از unsignedBigInteger به جای foreignId
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('restored_by')->nullable();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
