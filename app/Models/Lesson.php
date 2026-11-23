<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'topic_id',
        'title',
        'description',
        'thumbnail_path',
        'video_type',
        'video_path',
        'video_url',
        'notes_path',
        'worksheet_path',
        'slides_path',
        'image_paths',
        'interactive_url',
        'summary_text',
        'vocabulary_list',
        'important_points',
        'status',
        'approval_status',
        'admin_note',
        'last_moderated_by_admin_id',
        'last_moderated_at',
        'is_autism_friendly',
        'accessibility_tags',
    ];

    protected $casts = [
        'image_paths'            => 'array',
        'accessibility_tags'     => 'array',
        'is_autism_friendly'     => 'boolean',
        'last_moderated_at'      => 'datetime',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function chapter()
    {
        // convenience shortcut: lesson -> topic -> chapter
        return $this->hasOneThrough(
            Chapter::class,
            Topic::class,
            'id',         // Topic primary key
            'id',         // Chapter primary key
            'topic_id',   // Lesson foreign key to Topic
            'chapter_id'  // Topic foreign key to Chapter
        );
    }

    public function course()
    {
        // lesson -> topic -> chapter -> course
        return $this->hasOneThrough(
            Course::class,
            Chapter::class,
            'id',         // Chapter primary key
            'id',         // Course primary key
            'topic_id',   // Lesson foreign key to Topic
            'course_id'   // Chapter foreign key to Course
        );
    }

    public function doubts()
{
    return $this->hasMany(Doubt::class);
}


}
