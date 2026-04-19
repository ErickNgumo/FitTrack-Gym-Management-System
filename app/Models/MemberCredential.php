<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Separate auth model for the Member Portal.
 * Uses the `member` guard defined in config/auth.php.
 */
class MemberCredential extends Authenticatable
{
    use Notifiable;

    protected $table    = 'member_credentials';
    protected $fillable = ['member_id', 'password', 'must_change_pw', 'last_login'];
    protected $hidden   = ['password', 'remember_token'];

    protected $casts = [
        'must_change_pw' => 'boolean',
        'last_login'     => 'datetime',
    ];

    /** The underlying Member record. */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Convenience: login identifier is the member's phone number.
     * Members don't type email — they use their phone (easy to remember).
     */
    public function getAuthIdentifierName(): string
    {
        return 'member_id';
    }
}
