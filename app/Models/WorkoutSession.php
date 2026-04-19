<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutSession extends Model
{
    protected $fillable = [
        'member_id', 'trainer_id', 'session_date',
        'duration_mins', 'session_type', 'overall_notes', 'member_feedback',
    ];

    protected $casts = ['session_date' => 'date'];

    public function member()    { return $this->belongsTo(Member::class); }
    public function trainer()   { return $this->belongsTo(Trainer::class); }
    public function exercises() { return $this->hasMany(WorkoutExercise::class, 'session_id')->orderBy('sort_order'); }
}
