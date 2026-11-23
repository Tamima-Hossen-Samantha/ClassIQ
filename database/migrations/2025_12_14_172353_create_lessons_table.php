<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();

            // Link to topic (one topic can have one or many lessons; for now 1:many)
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();

            // Basic info
            $table->string('title');
            $table->text('description')->nullable();       // summary text
            $table->string('thumbnail_path')->nullable();  // lesson thumbnail

            // Video
            $table->string('video_type')->nullable();      // 'upload', 'youtube', 'vimeo', 'external'
            $table->string('video_path')->nullable();      // for uploaded mp4: storage path
            $table->string('video_url')->nullable();       // for YouTube/embed URL

            // Files
            $table->string('notes_path')->nullable();      // PDF/DOC notes
            $table->string('worksheet_path')->nullable();  // PDF worksheet
            $table->string('slides_path')->nullable();     // PPT/PPTX/Keynote

            // Images: store as JSON array of paths
            $table->json('image_paths')->nullable();

            // Interactive activity (external URL / embed code)
            $table->string('interactive_url')->nullable();

            // Structured text pieces
            $table->text('summary_text')->nullable();      // simple explanation
            $table->text('vocabulary_list')->nullable();   // maybe one word per line (later we can parse)
            $table->text('important_points')->nullable();  // bullet-style text

            // Status for teacher + admin
            $table->string('status')->default('draft');           // draft, published
            $table->string('approval_status')->default('pending'); // pending, approved, rejected

            // Admin moderation info
            $table->text('admin_note')->nullable();
            $table->foreignId('last_moderated_by_admin_id')
                  ->nullable()
                  ->constrained('admins')
                  ->nullOnDelete();
            $table->timestamp('last_moderated_at')->nullable();

            // Accessibility flags
            $table->boolean('is_autism_friendly')->default(false); // teacher can mark
            $table->json('accessibility_tags')->nullable();        // admin can add tags like ["dyslexia-friendly","subtitles"]

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};

