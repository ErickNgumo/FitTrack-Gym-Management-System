<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $fillable = [
        'member_number', 'name', 'phone', 'email', 'gender',
        'date_of_birth', 'address', 'emergency_contact_name',
        'emergency_contact_phone', 'photo_path', 'join_date',
        'status', 'trainer_id', 'notes', 'created_by',
    ];

    protected $casts = [
        'join_date'     => 'date',
        'date_of_birth' => 'date',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // ── Helpers ────────────────────────────────────────────────

    /** Returns the currently active subscription, or null. */
    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->latest('end_date')
            ->first();
    }

    public function isSubscriptionActive(): bool
    {
        return $this->activeSubscription() !== null;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'active'    => 'success',
            'inactive'  => 'secondary',
            'suspended' => 'danger',
            default     => 'secondary',
        };
    }
}
