<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_learner', function (Blueprint $table) {
            $table->id();

            // link to courses table
            $table->foreignId('course_id')
                  ->constrained('courses')
                  ->cascadeOnDelete();

            // link to learners table (your auth table)
            $table->foreignId('learner_id')
                  ->constrained('learners')
                  ->cascadeOnDelete();

            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['course_id', 'learner_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_learner');
    }
};
