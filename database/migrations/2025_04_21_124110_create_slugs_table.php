<?php

use App\Enums\CommonStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('slugs', function (Blueprint $table) {
            $table->id();
            $table->string('title_persian', 100)->unique();
            $table->string('title_english', 100)->unique();
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
            $table->string("status")->default(CommonStatus::ACTIVE->value);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slugs');
    }
};
