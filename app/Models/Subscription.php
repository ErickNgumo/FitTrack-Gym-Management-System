<?php
// ── Subscription.php ──────────────────────────────────────────────────────────
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'member_id', 'plan_id', 'start_date', 'end_date',
        'status', 'notes', 'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function member()   { return $this->belongsTo(Member::class); }
    public function plan()     { return $this->belongsTo(MembershipPlan::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function createdBy(){ return $this->belongsTo(User::class, 'created_by'); }

    public function isExpired(): bool
    {
        return $this->end_date->isPast();
    }
}
