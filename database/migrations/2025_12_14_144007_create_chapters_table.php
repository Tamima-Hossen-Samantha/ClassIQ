<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();

            // Each chapter belongs to a course
            $table->foreignId('course_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            // Order inside the course
            $table->unsignedInteger('position')->default(1);

            // Draft / published
            $table->string('status')->default('draft'); // draft, published

            // Extra info
            $table->string('estimated_time')->nullable(); // e.g. "45 min"
            $table->string('difficulty')->nullable();     // e.g. "easy","medium","hard"

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};

