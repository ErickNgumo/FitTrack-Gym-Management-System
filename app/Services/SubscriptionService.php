<?php

namespace App\Services;

use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    /**
     * Create a new subscription for a member.
     * If the member has an active subscription, the new one starts after it ends.
     */
    public function create(Member $member, MembershipPlan $plan, ?string $startDate = null): Subscription
    {
        $active = $member->activeSubscription();

        // If no explicit start date, start today (or after current sub ends if still active)
        $start = $startDate
            ? Carbon::parse($startDate)
            : ($active ? $active->end_date->addDay() : now());

        $end = $start->copy()->addDays($plan->duration_days - 1);

        return Subscription::create([
            'member_id'  => $member->id,
            'plan_id'    => $plan->id,
            'start_date' => $start->toDateString(),
            'end_date'   => $end->toDateString(),
            'status'     => 'active',
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Cancel a subscription.
     */
    public function cancel(Subscription $subscription): void
    {
        $subscription->update(['status' => 'cancelled']);
    }

    /**
     * Mark all past-due subscriptions as expired (run via scheduler or artisan command).
     */
    public function expireOutdated(): int
    {
        return Subscription::where('status', 'active')
            ->where('end_date', '<', now()->toDateString())
            ->update(['status' => 'expired']);
    }

    /**
     * Get subscriptions expiring within a given window.
     */
    public function getExpiring(int $days = 7)
    {
        return Subscription::with(['member', 'plan'])
            ->where('status', 'active')
            ->whereBetween('end_date', [now()->toDateString(), now()->addDays($days)->toDateString()])
            ->orderBy('end_date')
            ->get();
    }
}
