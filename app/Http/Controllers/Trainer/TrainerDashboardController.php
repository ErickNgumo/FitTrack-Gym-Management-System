<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\TrainerMemberNote;
use App\Models\WorkoutExercise;
use App\Models\WorkoutSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerDashboardController extends Controller
{
    private function authTrainer(): \App\Models\Trainer
    {
        return Auth::guard('trainer')->user()->trainer;
    }

    // ── Dashboard ─────────────────────────────────────────────

    public function dashboard()
    {
        $trainer = $this->authTrainer();
        $members = Member::where('trainer_id', $trainer->id)
            ->where('status', 'active')
            ->with(['subscriptions' => fn($q) => $q->where('status', 'active')])
            ->get();

        $todaySessions = WorkoutSession::where('trainer_id', $trainer->id)
            ->whereDate('session_date', today())
            ->with('member')
            ->get();

        $recentSessions = WorkoutSession::where('trainer_id', $trainer->id)
            ->with('member')
            ->orderByDesc('session_date')
            ->take(5)
            ->get();

        return view('trainer-portal.dashboard', compact(
            'trainer', 'members', 'todaySessions', 'recentSessions'
        ));
    }

    // ── Member list ───────────────────────────────────────────

    public function members()
    {
        $trainer = $this->authTrainer();
        $members = Member::where('trainer_id', $trainer->id)
            ->with(['subscriptions' => fn($q) => $q->where('status', 'active')->with('plan')])
            ->orderBy('name')
            ->get();

        return view('trainer-portal.members', compact('trainer', 'members'));
    }

    // ── Member detail (trainer view) ──────────────────────────

    public function memberShow(Member $member)
    {
        $trainer = $this->authTrainer();
        abort_if($member->trainer_id !== $trainer->id, 403, 'This member is not assigned to you.');

        $sessions = WorkoutSession::where('member_id', $member->id)
            ->where('trainer_id', $trainer->id)
            ->with('exercises')
            ->orderByDesc('session_date')
            ->paginate(10);

        $notes = TrainerMemberNote::where('member_id', $member->id)
            ->where('trainer_id', $trainer->id)
            ->orderByDesc('created_at')
            ->get();

        $attendance = $member->attendance()->orderByDesc('check_in_time')->take(10)->get();
        $activeSub  = $member->activeSubscription();

        return view('trainer-portal.member-show', compact(
            'trainer', 'member', 'sessions', 'notes', 'attendance', 'activeSub'
        ));
    }

    // ── Workout Sessions ──────────────────────────────────────

    public function sessionCreate(Member $member)
    {
        $trainer = $this->authTrainer();
        abort_if($member->trainer_id !== $trainer->id, 403);
        return view('trainer-portal.session-create', compact('trainer', 'member'));
    }

    public function sessionStore(Request $request, Member $member)
    {
        $trainer = $this->authTrainer();
        abort_if($member->trainer_id !== $trainer->id, 403);

        $validated = $request->validate([
            'session_date'          => 'required|date',
            'duration_mins'         => 'nullable|integer|min:1|max:300',
            'session_type'          => 'nullable|string|max:80',
            'overall_notes'         => 'nullable|string',
            'exercises'             => 'nullable|array',
            'exercises.*.name'      => 'required_with:exercises|string|max:100',
            'exercises.*.sets'      => 'nullable|integer|min:1',
            'exercises.*.reps'      => 'nullable|integer|min:1',
            'exercises.*.weight_kg' => 'nullable|numeric|min:0',
            'exercises.*.duration_secs' => 'nullable|integer|min:1',
            'exercises.*.distance_m'    => 'nullable|numeric|min:0',
            'exercises.*.notes'         => 'nullable|string|max:255',
        ]);

        $session = WorkoutSession::create([
            'member_id'     => $member->id,
            'trainer_id'    => $trainer->id,
            'session_date'  => $validated['session_date'],
            'duration_mins' => $validated['duration_mins'] ?? null,
            'session_type'  => $validated['session_type'] ?? null,
            'overall_notes' => $validated['overall_notes'] ?? null,
        ]);

        foreach (($validated['exercises'] ?? []) as $i => $ex) {
            WorkoutExercise::create([
                'session_id'    => $session->id,
                'exercise_name' => $ex['name'],
                'sets'          => $ex['sets'] ?? null,
                'reps'          => $ex['reps'] ?? null,
                'weight_kg'     => $ex['weight_kg'] ?? null,
                'duration_secs' => $ex['duration_secs'] ?? null,
                'distance_m'    => $ex['distance_m'] ?? null,
                'notes'         => $ex['notes'] ?? null,
                'sort_order'    => $i,
            ]);
        }

        return redirect()
            ->route('trainer.member.show', $member)
            ->with('success', 'Workout session logged successfully.');
    }

    public function sessionShow(WorkoutSession $session)
    {
        $trainer = $this->authTrainer();
        abort_if($session->trainer_id !== $trainer->id, 403);
        $session->load(['member', 'exercises']);
        return view('trainer-portal.session-show', compact('trainer', 'session'));
    }

    // ── Quick Notes ───────────────────────────────────────────

    public function noteStore(Request $request, Member $member)
    {
        $trainer = $this->authTrainer();
        abort_if($member->trainer_id !== $trainer->id, 403);

        $request->validate([
            'note'       => 'required|string|max:2000',
            'is_private' => 'boolean',
        ]);

        TrainerMemberNote::create([
            'trainer_id' => $trainer->id,
            'member_id'  => $member->id,
            'note'       => $request->note,
            'is_private' => $request->boolean('is_private'),
        ]);

        return back()->with('success', 'Note saved.');
    }

    public function noteDestroy(TrainerMemberNote $note)
    {
        $trainer = $this->authTrainer();
        abort_if($note->trainer_id !== $trainer->id, 403);
        $note->delete();
        return back()->with('success', 'Note deleted.');
    }
}
