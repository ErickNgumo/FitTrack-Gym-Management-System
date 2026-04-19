<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\WorkoutSession;
use Illuminate\Support\Facades\Auth;

class MemberDashboardController extends Controller
{
    private function authMember(): \App\Models\Member
    {
        return Auth::guard('member')->user()->member;
    }

    public function dashboard()
    {
        $member      = $this->authMember();
        $activeSub   = $member->activeSubscription();
        $recentAtt   = $member->attendance()->orderByDesc('check_in_time')->take(5)->get();
        $recentSessions = WorkoutSession::where('member_id', $member->id)
            ->with(['trainer', 'exercises'])
            ->orderByDesc('session_date')
            ->take(3)
            ->get();
        $totalVisits = $member->attendance()->count();
        $thisMonthVisits = $member->attendance()
            ->whereMonth('check_in_time', now()->month)
            ->whereYear('check_in_time', now()->year)
            ->count();

        return view('member.dashboard', compact(
            'member', 'activeSub', 'recentAtt',
            'recentSessions', 'totalVisits', 'thisMonthVisits'
        ));
    }

    public function attendance()
    {
        $member  = $this->authMember();
        $records = $member->attendance()->orderByDesc('check_in_time')->paginate(20);
        return view('member.attendance', compact('member', 'records'));
    }

    public function payments()
    {
        $member   = $this->authMember();
        $payments = $member->payments()->with('subscription.plan')->orderByDesc('payment_date')->paginate(20);
        return view('member.payments', compact('member', 'payments'));
    }

    public function workouts()
    {
        $member   = $this->authMember();
        $sessions = WorkoutSession::where('member_id', $member->id)
            ->with(['trainer', 'exercises'])
            ->orderByDesc('session_date')
            ->paginate(10);
        return view('member.workouts', compact('member', 'sessions'));
    }

    public function workoutShow(WorkoutSession $session)
    {
        $member = $this->authMember();

        // Gate: member can only see their own sessions
        abort_if($session->member_id !== $member->id, 403);

        $session->load(['trainer', 'exercises']);
        return view('member.workout-show', compact('member', 'session'));
    }

    /** Member adds feedback to a workout session */
    public function submitFeedback(\Illuminate\Http\Request $request, WorkoutSession $session)
    {
        $member = $this->authMember();
        abort_if($session->member_id !== $member->id, 403);

        $request->validate(['member_feedback' => 'required|string|max:1000']);
        $session->update(['member_feedback' => $request->member_feedback]);

        return back()->with('success', 'Feedback saved.');
    }

    public function subscriptions()
    {
        $member = $this->authMember();
        $subs   = $member->subscriptions()->with('plan')->orderByDesc('start_date')->get();
        return view('member.subscriptions', compact('member', 'subs'));
    }
}
