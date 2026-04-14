<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active', 'last_login',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active'  => 'boolean',
        'last_login' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function activityLog()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
