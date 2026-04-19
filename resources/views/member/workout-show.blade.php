@extends('member.layouts.app')
@section('title', 'Workout Session')
@section('page-title', 'Workout Session')

@section('content')

<div style="margin-bottom:16px;">
    <a href="{{ route('member.workouts') }}" style="font-size:.85rem;color:var(--m-muted);text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Back to Workouts
    </a>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-8">
        {{-- Session header --}}
        <div class="m-card mb-3">
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
                <div style="width:56px;height:56px;border-radius:14px;background:#f0f4ff;display:flex;align-items:center;justify-content:center;font-size:1.6rem;flex-shrink:0;">
                    {{ match(strtolower($session->session_type ?? '')) {
                        'strength','weights' => '🏋️',
                        'cardio'  => '🏃',
                        'yoga'    => '🧘',
                        'hiit'    => '⚡',
                        default   => '💪'
                    } }}
                </div>
                <div>
                    <h4 style="font-size:1.1rem;margin-bottom:4px;">{{ $session->session_type ?? 'Workout Session' }}</h4>
                    <div style="font-size:.82rem;color:var(--m-muted);">
                        {{ $session->session_date->format('l, d M Y') }}
                        @if($session->duration_mins) &nbsp;·&nbsp; {{ $session->duration_mins }} minutes @endif
                        &nbsp;·&nbsp; With {{ $session->trainer->name }}
                    </div>
                </div>
            </div>

            @if($session->overall_notes)
            <div style="background:#f0f4ff;border-radius:10px;padding:14px 16px;margin-bottom:4px;">
                <div style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--m-muted);margin-bottom:6px;">Trainer's Notes</div>
                <p style="font-size:.875rem;margin:0;line-height:1.6;">{{ $session->overall_notes }}</p>
            </div>
            @endif
        </div>

        {{-- Exercises table --}}
        <div class="m-card mb-3" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--m-border);">
                <h5 style="font-size:1rem;margin:0;">Exercises ({{ $session->exercises->count() }})</h5>
            </div>
            <table class="m-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Exercise</th>
                        <th>Sets</th>
                        <th>Reps</th>
                        <th>Weight</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($session->exercises as $i => $ex)
                    <tr>
                        <td style="color:var(--m-muted);font-size:.8rem;">{{ $i + 1 }}</td>
                        <td><strong>{{ $ex->exercise_name }}</strong></td>
                        <td>{{ $ex->sets ?? '—' }}</td>
                        <td>{{ $ex->reps ?? ($ex->duration_secs ? gmdate('i:s', $ex->duration_secs) : '—') }}</td>
                        <td>
                            @if($ex->weight_kg)
                                {{ $ex->weight_kg }} kg
                            @elseif($ex->distance_m)
                                {{ number_format($ex->distance_m) }} m
                            @else
                                —
                            @endif
                        </td>
                        <td style="font-size:.8rem;color:var(--m-muted);">{{ $ex->notes ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:30px;color:var(--m-muted);">No exercises recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Member feedback --}}
        <div class="m-card">
            <h5 style="font-size:1rem;margin-bottom:14px;">Your Feedback</h5>
            @if($session->member_feedback)
                <div style="background:#f0f4ff;border-radius:10px;padding:14px 16px;margin-bottom:14px;">
                    <p style="font-size:.875rem;margin:0;line-height:1.6;">{{ $session->member_feedback }}</p>
                </div>
                <p style="font-size:.78rem;color:var(--m-muted);margin:0;">Feedback already submitted. Update it below.</p>
                <div style="height:1px;background:var(--m-border);margin:14px 0;"></div>
            @endif

            <form method="POST" action="{{ route('member.workout.feedback', $session) }}">
                @csrf
                <label class="m-label">How did the session feel?</label>
                <textarea class="m-input" name="member_feedback" rows="3"
                          placeholder="e.g. Felt great! Shoulders are a bit sore. Can we increase deadlift weight next time?"
                          style="resize:vertical;">{{ old('member_feedback', $session->member_feedback) }}</textarea>
                <button type="submit" class="m-btn-primary" style="margin-top:10px;">
                    <i class="bi bi-chat-dots"></i> Submit Feedback
                </button>
            </form>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="m-card">
            <h5 style="font-size:.85rem;margin-bottom:14px;color:var(--m-muted);text-transform:uppercase;letter-spacing:.05em;">Session Summary</h5>
            <div style="font-size:.875rem;display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--m-muted);">Date</span>
                    <strong>{{ $session->session_date->format('d M Y') }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--m-muted);">Duration</span>
                    <strong>{{ $session->duration_mins ? $session->duration_mins . ' min' : '—' }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--m-muted);">Type</span>
                    <strong>{{ $session->session_type ?? '—' }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--m-muted);">Trainer</span>
                    <strong>{{ $session->trainer->name }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--m-muted);">Exercises</span>
                    <strong>{{ $session->exercises->count() }}</strong>
                </div>
                @php
                    $totalSets = $session->exercises->sum('sets');
                    $totalWeight = $session->exercises->sum(fn($e) => ($e->sets ?? 1) * ($e->reps ?? 1) * ($e->weight_kg ?? 0));
                @endphp
                @if($totalSets)
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--m-muted);">Total Sets</span>
                    <strong>{{ $totalSets }}</strong>
                </div>
                @endif
                @if($totalWeight > 0)
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--m-muted);">Volume</span>
                    <strong>{{ number_format($totalWeight) }} kg</strong>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--m-muted);">Feedback</span>
                    @if($session->member_feedback)
                        <span class="m-badge m-badge-green">Submitted</span>
                    @else
                        <span class="m-badge m-badge-yellow">Pending</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
