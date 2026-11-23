<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Course;

class Learner extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'class_grade',
        'board',
        'preferred_language',
        'special_need',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Progress relation
    public function topicProgress()
    {
        return $this->hasMany(\App\Models\TopicUserProgress::class, 'learner_id');
    }

    
    public function courses()
    {
        // pivot table course_learner and has joined_at
        return $this->belongsToMany(Course::class, 'course_learner')
            ->withTimestamps()
            ->withPivot('joined_at');
    }

    public function doubts()
{
    return $this->hasMany(Doubt::class);
}

protected $casts = [
    'accessibility_settings' => 'array',
    'calm_mode' => true,
  'dyslexia_font' => true,
  'large_text' => true,
  'no_emojis' => false,
];



}
