<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
    'chapter_id',
    'title',
    'content',
    'position',
    'status',
    'video_url',
    'resource_url',
    'approval_status',
    'admin_note',
    'last_moderated_by_admin_id',
    'last_moderated_at',
];

protected $casts = [
    'last_moderated_at' => 'datetime',
];


    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            Chapter::class,
            'id',        
            'id',        
            'chapter_id',
            'course_id'  
        );
    }

    public function progress()
    {
        return $this->hasMany(TopicUserProgress::class);
    }

    public function lessons()
{
    return $this->hasMany(Lesson::class)->orderBy('created_at');
}

}
