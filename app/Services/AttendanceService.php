<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Member;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Check in a member. Validates active subscription first.
     *
     * @throws \RuntimeException if member has no active subscription.
     */
    public function checkIn(Member $member): Attendance
    {
        if (! $member->isSubscriptionActive()) {
            throw new \RuntimeException("Member {$member->name} does not have an active subscription.");
        }

        // Prevent double check-in on same day
        $existing = Attendance::where('member_id', $member->id)
            ->whereDate('check_in_time', today())
            ->whereNull('check_out_time')
            ->first();

        if ($existing) {
            throw new \RuntimeException("{$member->name} is already checked in today.");
        }

        return Attendance::create([
            'member_id'     => $member->id,
            'check_in_time' => now(),
            'recorded_by'   => auth()->id(),
        ]);
    }

    /**
     * Check out a member for today's open record.
     */
    public function checkOut(Member $member): Attendance
    {
        $record = Attendance::where('member_id', $member->id)
            ->whereDate('check_in_time', today())
            ->whereNull('check_out_time')
            ->firstOrFail();

        $record->update(['check_out_time' => now()]);
        return $record;
    }

    /**
     * Today's check-in count.
     */
    public function todayCount(): int
    {
        return Attendance::whereDate('check_in_time', today())->count();
    }

    /**
     * Top attending members in last N days.
     */
    public function topMembers(int $days = 30, int $limit = 10): \Illuminate\Support\Collection
    {
        return Attendance::selectRaw('member_id, COUNT(*) as visits')
            ->where('check_in_time', '>=', now()->subDays($days))
            ->groupBy('member_id')
            ->orderByDesc('visits')
            ->limit($limit)
            ->with('member:id,name,member_number')
            ->get();
    }
}
