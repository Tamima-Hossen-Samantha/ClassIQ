<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('school_name')->nullable();
            $table->string('subjects_taught')->nullable();
            $table->string('classes_taught')->nullable();

            $table->string('nid_file_path')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_approved')->default(false);

            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
