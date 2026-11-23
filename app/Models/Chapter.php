<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
    'course_id',
    'title',
    'description',
    'position',
    'status',
    'estimated_time',
    'difficulty',
    'approval_status',
    'admin_note',
    'last_moderated_by_admin_id',
    'last_moderated_at',
];

protected $casts = [
    'last_moderated_at' => 'datetime',
];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function topics()
    {
        // Single topics() relation, ordered by position
        return $this->hasMany(\App\Models\Topic::class)->orderBy('position');
    }


    public function lessons()
{
    return $this->hasManyThrough(Lesson::class, Topic::class);
}

}
