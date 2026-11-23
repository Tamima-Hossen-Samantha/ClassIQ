<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            // ownership
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();

            // core info
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('thumbnail_path')->nullable();

            // school structure
            $table->unsignedTinyInteger('class_level');     // 1–12
            $table->string('subject');                      // e.g. "Math"

            // meta
            $table->string('difficulty')->default('medium');   // easy/medium/hard
            $table->string('visibility')->default('private');  // private/school/public

            // status
            $table->string('status')->default('draft');        // draft/published
            $table->boolean('is_joinable')->default(true);

            // extras
            $table->string('tags')->nullable();                // comma-separated tags
            $table->string('estimated_duration')->nullable();  // "8 weeks"
            $table->string('prerequisite')->nullable();        // text note

            // class code
            $table->string('join_code', 16)->nullable()->unique();
            $table->timestamp('join_code_expires_at')->nullable();

            // simple analytics counters
            $table->unsignedInteger('enrolled_learners_count')->default(0);
            $table->unsignedInteger('lessons_count')->default(0);
            $table->unsignedInteger('quizzes_count')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
