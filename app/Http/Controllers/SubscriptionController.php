<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(private SubscriptionService $service) {}

    public function create(Member $member)
    {
        $plans = MembershipPlan::where('is_active', true)->orderBy('duration_days')->get();
        return view('subscriptions.create', compact('member', 'plans'));
    }

    public function store(Request $request, Member $member)
    {
        $validated = $request->validate([
            'plan_id'    => 'required|exists:membership_plans,id',
            'start_date' => 'nullable|date',
        ]);

        $plan = MembershipPlan::findOrFail($validated['plan_id']);
        $sub  = $this->service->create($member, $plan, $validated['start_date'] ?? null);

        return redirect()
            ->route('payments.create', ['member_id' => $member->id, 'subscription_id' => $sub->id])
            ->with('success', "Subscription created. Please record the payment.");
    }

    public function cancel(Subscription $subscription)
    {
        $this->service->cancel($subscription);
        return back()->with('success', 'Subscription cancelled.');
    }
}
