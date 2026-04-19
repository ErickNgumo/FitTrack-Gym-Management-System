<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutExercise extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'session_id', 'exercise_name', 'sets', 'reps',
        'weight_kg', 'duration_secs', 'distance_m', 'notes', 'sort_order',
    ];

    protected $casts = [
        'weight_kg'   => 'decimal:2',
        'distance_m'  => 'decimal:2',
    ];

    public function session() { return $this->belongsTo(WorkoutSession::class); }
}
