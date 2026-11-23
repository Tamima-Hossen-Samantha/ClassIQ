<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topic_user_progress', function (Blueprint $table) {
            $table->id();

            $table->foreignId('topic_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('learner_id')
                ->constrained('learners')
                ->cascadeOnDelete();

            // completed, in_progress, skipped, etc.
            $table->string('status')->default('completed');
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->unique(['topic_id', 'learner_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topic_user_progress');
    }
};
