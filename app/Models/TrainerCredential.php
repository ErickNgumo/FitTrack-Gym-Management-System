<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Separate auth model for the Trainer Portal.
 * Uses the `trainer` guard defined in config/auth.php.
 */
class TrainerCredential extends Authenticatable
{
    use Notifiable;

    protected $table    = 'trainer_credentials';
    protected $fillable = ['trainer_id', 'password', 'must_change_pw', 'last_login'];
    protected $hidden   = ['password', 'remember_token'];

    protected $casts = [
        'must_change_pw' => 'boolean',
        'last_login'     => 'datetime',
    ];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
}
