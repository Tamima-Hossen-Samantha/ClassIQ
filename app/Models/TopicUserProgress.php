<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicUserProgress extends Model
{
    protected $table = 'topic_user_progress';

    protected $fillable = [
        'topic_id',
        'learner_id',
        'status',
        'completed_at',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function learner()
    {
        return $this->belongsTo(Learner::class);
    }
}
