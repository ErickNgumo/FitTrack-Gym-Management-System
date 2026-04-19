<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'speciality', 'bio', 'status'];

    public function members() { return $this->hasMany(Member::class); }

    public function credentials()
    {
        return $this->hasOne(TrainerCredential::class);
    }

    public function workoutSessions()
    {
        return $this->hasMany(WorkoutSession::class);
    }

    public function trainerNotes()
    {
        return $this->hasMany(TrainerMemberNote::class);
    }
}
