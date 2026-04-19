@extends('trainer-portal.layouts.app')
@section('title', 'Session Detail')
@section('page-title', 'Session Detail')

@section('content')

<div style="margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
    <a href="{{ route('trainer.member.show', $session->member) }}" style="font-size:.85rem;color:var(--t-muted);text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Back to {{ $session->member->name }}
    </a>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-8">
        <div class="t-card mb-3">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;">
                <div style="width:52px;height:52px;border-radius:14px;background:#f0faf5;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">
                    {{ match(strtolower($session->session_type ?? '')) {
                        'strength','weights' => '🏋️',
                        'cardio'  => '🏃',
                        'yoga'    => '🧘',
                        'hiit'    => '⚡',
                        default   => '💪'
                    } }}
                </div>
                <div>
                    <h4 style="font-size:1.1rem;margin-bottom:3px;">{{ $session->session_type ?? 'Workout Session' }}</h4>
                    <div style="font-size:.8rem;color:var(--t-muted);">
                        {{ $session->session_date->format('l, d M Y') }}
                        @if($session->duration_mins) · {{ $session->duration_mins }} minutes @endif
                    </div>
                </div>
            </div>

            @if($session->overall_notes)
            <div style="background:#f0faf5;border-radius:10px;padding:14px 16px;margin-bottom:4px;">
                <div style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--t-muted);margin-bottom:6px;">Your Notes</div>
                <p style="font-size:.875rem;margin:0;line-height:1.6;">{{ $session->overall_notes }}</p>
            </div>
            @endif
        </div>

        <div class="t-card mb-3" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--t-border);">
                <h5 style="font-size:1rem;margin:0;">Exercises ({{ $session->exercises->count() }})</h5>
            </div>
            <table class="t-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Exercise</th>
                        <th>Sets</th>
                        <th>Reps</th>
                        <th>Weight / Distance</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($session->exercises as $i => $ex)
                    <tr>
                        <td style="color:var(--t-muted);font-size:.8rem;">{{ $i + 1 }}</td>
                        <td><strong>{{ $ex->exercise_name }}</strong></td>
                        <td>{{ $ex->sets ?? '—' }}</td>
                        <td>{{ $ex->reps ?? ($ex->duration_secs ? gmdate('i:s', $ex->duration_secs) : '—') }}</td>
                        <td>
                            @if($ex->weight_kg) {{ $ex->weight_kg }} kg
                            @elseif($ex->distance_m) {{ number_format($ex->distance_m) }} m
                            @else —
                            @endif
                        </td>
                        <td style="font-size:.8rem;color:var(--t-muted);">{{ $ex->notes ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:30px;color:var(--t-muted);">No exercises recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Member feedback (read-only for trainer) --}}
        @if($session->member_feedback)
        <div class="t-card">
            <h5 style="font-size:1rem;margin-bottom:12px;">Member Feedback</h5>
            <div style="background:#f0faf5;border-left:3px solid var(--t-accent);border-radius:0 10px 10px 0;padding:14px 16px;">
                <p style="font-size:.875rem;margin:0;line-height:1.6;font-style:italic;">"{{ $session->member_feedback }}"</p>
            </div>
            <div style="font-size:.75rem;color:var(--t-muted);margin-top:8px;">— {{ $session->member->name }}</div>
        </div>
        @else
        <div class="t-card" style="text-align:center;padding:24px;color:var(--t-muted);">
            <i class="bi bi-chat-dots" style="font-size:1.5rem;display:block;margin-bottom:8px;opacity:.4;"></i>
            <p style="font-size:.85rem;margin:0;">Member hasn't left feedback on this session yet.</p>
        </div>
        @endif
    </div>

    <div class="col-12 col-xl-4">
        <div class="t-card">
            <h5 style="font-size:.85rem;margin-bottom:14px;color:var(--t-muted);text-transform:uppercase;letter-spacing:.05em;">Session Summary</h5>
            <div style="font-size:.875rem;display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--t-muted);">Member</span>
                    <strong>{{ $session->member->name }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--t-muted);">Date</span>
                    <strong>{{ $session->session_date->format('d M Y') }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--t-muted);">Duration</span>
                    <strong>{{ $session->duration_mins ? $session->duration_mins . ' min' : '—' }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--t-muted);">Type</span>
                    <strong>{{ $session->session_type ?? '—' }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--t-muted);">Exercises</span>
                    <strong>{{ $session->exercises->count() }}</strong>
                </div>
                @php $vol = $session->exercises->sum(fn($e) => ($e->sets ?? 1) * ($e->reps ?? 1) * ($e->weight_kg ?? 0)); @endphp
                @if($vol > 0)
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--t-muted);">Total Volume</span>
                    <strong>{{ number_format($vol) }} kg</strong>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--t-muted);">Feedback</span>
                    @if($session->member_feedback)
                        <span class="t-badge t-badge-green">Received ✓</span>
                    @else
                        <span class="t-badge t-badge-yellow">Awaiting</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
