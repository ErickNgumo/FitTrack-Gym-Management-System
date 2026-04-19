@extends('member.layouts.app')
@section('title', 'My Workouts')
@section('page-title', 'My Workouts')

@section('content')

<div style="margin-bottom:20px;">
    <h4 style="font-size:1.15rem;margin:0;">Workout History</h4>
    <p style="font-size:.82rem;color:var(--m-muted);margin:0;">Sessions logged by your trainer</p>
</div>

@forelse($sessions as $session)
<a href="{{ route('member.workout.show', $session) }}" style="text-decoration:none;color:inherit;">
<div class="m-card mb-3" style="transition:box-shadow .15s;cursor:pointer;" onmouseover="this.style.boxShadow='0 4px 20px rgba(15,52,96,.08)'" onmouseout="this.style.boxShadow=''">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
        <div style="display:flex;gap:14px;align-items:center;">
            <div style="width:48px;height:48px;border-radius:12px;background:#f0f4ff;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
                {{ match(strtolower($session->session_type ?? '')) {
                    'strength','weights' => '🏋️',
                    'cardio'  => '🏃',
                    'yoga'    => '🧘',
                    'hiit'    => '⚡',
                    default   => '💪'
                } }}
            </div>
            <div>
                <div style="font-weight:600;font-size:.95rem;">{{ $session->session_type ?? 'Workout Session' }}</div>
                <div style="font-size:.78rem;color:var(--m-muted);margin-top:2px;">
                    With {{ $session->trainer->name }}
                    @if($session->duration_mins) &nbsp;·&nbsp; {{ $session->duration_mins }} mins @endif
                </div>
                <div style="margin-top:8px;display:flex;flex-wrap:wrap;gap:5px;">
                    @foreach($session->exercises->take(4) as $ex)
                        <span style="background:#f0f4ff;color:var(--m-primary);font-size:.7rem;padding:2px 8px;border-radius:6px;">{{ $ex->exercise_name }}</span>
                    @endforeach
                    @if($session->exercises->count() > 4)
                        <span style="background:#f0f4ff;color:var(--m-muted);font-size:.7rem;padding:2px 8px;border-radius:6px;">+{{ $session->exercises->count() - 4 }} more</span>
                    @endif
                </div>
            </div>
        </div>
        <div style="text-align:right;flex-shrink:0;">
            <div style="font-size:.85rem;font-weight:600;">{{ $session->session_date->format('d M Y') }}</div>
            <div style="font-size:.72rem;color:var(--m-muted);margin-top:3px;">{{ $session->exercises->count() }} exercises</div>
            <div style="margin-top:8px;">
                @if($session->member_feedback)
                    <span class="m-badge m-badge-green">Reviewed ✓</span>
                @else
                    <span class="m-badge m-badge-yellow">Awaiting feedback</span>
                @endif
            </div>
        </div>
    </div>
</div>
</a>
@empty
<div class="m-card" style="text-align:center;padding:60px 40px;color:var(--m-muted);">
    <div style="font-size:3rem;margin-bottom:14px;">💪</div>
    <h5 style="font-size:1rem;margin-bottom:6px;">No workout sessions yet</h5>
    <p style="font-size:.875rem;margin:0;">Your trainer will log sessions after each workout.</p>
</div>
@endforelse

@if($sessions->hasPages())
<div style="margin-top:16px;">{{ $sessions->links() }}</div>
@endif

@endsection
