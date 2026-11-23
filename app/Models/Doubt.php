<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doubt extends Model
{
    protected $fillable = [
        'lesson_id',
        'learner_id',
        'teacher_id',
        'question',
        'teacher_reply',
        'status',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function learner()
    {
        return $this->belongsTo(Learner::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}

