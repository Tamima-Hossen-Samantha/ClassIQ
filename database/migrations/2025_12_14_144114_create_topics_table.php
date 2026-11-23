<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();

            // Each topic belongs to a chapter
            $table->foreignId('chapter_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('content')->nullable(); // explanation / notes

            // Order inside a chapter
            $table->unsignedInteger('position')->default(1);

            // Topic visibility: even if chapter is published,
            // topic can be draft/hidden
            $table->string('status')->default('draft'); // draft, published

            // Optional resource links
            $table->string('video_url')->nullable();
            $table->string('resource_url')->nullable(); // PDF / worksheet / external

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};

