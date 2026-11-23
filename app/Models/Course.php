<?php

namespace App\Models;

use App\Models\Teacher;
use App\Models\Learner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // Mass-assignable fields
    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'thumbnail_path',
        'class_level',
        'subject',
        'difficulty',
        'visibility',
        'status',
        'admin_status',
        'is_joinable',
        'is_visible_in_catalog',
        'is_featured',
        'tags',
        'estimated_duration',
        'prerequisite',
        'join_code',
        'join_code_expires_at',
        'enrolled_learners_count',
        'lessons_count',
        'quizzes_count',
    ];

    protected $casts = [
        'is_joinable'            => 'boolean',
        'is_visible_in_catalog'  => 'boolean',
        'is_featured'            => 'boolean',
        'join_code_expires_at'   => 'datetime',
    ];

    // A course belongs to one teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Many-to-many: a course has many learners through course_learner
    public function learners()
    {
        return $this->belongsToMany(Learner::class, 'course_learner')
            ->withTimestamps()
            ->withPivot('joined_at');
    }

    // One-to-many: course has many chapters
    public function chapters()
    {
        return $this->hasMany(\App\Models\Chapter::class)->orderBy('position');
    }

public function lessons()
{
    return $this->hasManyThrough(Lesson::class, Chapter::class);
}


}
