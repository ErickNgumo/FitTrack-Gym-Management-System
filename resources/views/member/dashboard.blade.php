@extends('member.layouts.app')
@section('title', 'My Dashboard')
@section('page-title', 'My Dashboard')

@section('content')

{{-- Welcome strip --}}
<div style="margin-bottom:22px;">
    <h4 style="font-size:1.2rem;margin-bottom:2px;">Hey, {{ explode(' ', $member->name)[0] }} 👋</h4>
    <p style="font-size:.85rem;color:var(--m-muted);margin:0;">Here's how things are looking for you.</p>
</div>

{{-- Membership status banner --}}
@if($activeSub)
    <div class="m-alert m-alert-success" style="margin-bottom:22px;border-radius:14px;padding:16px 20px;">
        <i class="bi bi-shield-check-fill" style="font-size:1.2rem;margin-top:2px;"></i>
        <div>
            <strong>Membership Active</strong> — {{ $activeSub->plan->name }}<br>
            <span style="font-size:.82rem;">Expires <strong>{{ $activeSub->end_date->format('d M Y') }}</strong>
            &nbsp;·&nbsp; {{ $activeSub->end_date->diffInDays(now()) }} days remaining</span>
        </div>
    </div>
@else
    <div class="m-alert m-alert-warning" style="margin-bottom:22px;border-radius:14px;padding:16px 20px;">
        <i class="bi bi-exclamation-triangle-fill" style="font-size:1.2rem;margin-top:2px;"></i>
        <div>
            <strong>No Active Membership</strong><br>
            <span style="font-size:.82rem;">Please visit the front desk to renew your subscription.</span>
        </div>
    </div>
@endif

{{-- KPIs --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="m-kpi">
            <div class="m-kpi-icon" style="background:#dbeafe;color:#1d4ed8;"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="m-kpi-val">{{ $thisMonthVisits }}</div>
                <div class="m-kpi-label">Visits This Month</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="m-kpi">
            <div class="m-kpi-icon" style="background:#dcfce7;color:#15803d;"><i class="bi bi-trophy"></i></div>
            <div>
                <div class="m-kpi-val">{{ $totalVisits }}</div>
                <div class="m-kpi-label">Total Visits</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="m-kpi">
            <div class="m-kpi-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-activity"></i></div>
            <div>
                <div class="m-kpi-val">{{ $recentSessions->count() }}</div>
                <div class="m-kpi-label">Recent Sessions</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="m-kpi">
            <div class="m-kpi-icon" style="background:#f3e8ff;color:#7c3aed;"><i class="bi bi-person-badge"></i></div>
            <div>
                <div class="m-kpi-val" style="font-size:1rem;">{{ $member->trainer?->name ?? 'None' }}</div>
                <div class="m-kpi-label">My Trainer</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Recent workouts --}}
    <div class="col-12 col-xl-7">
        <div class="m-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
                <h5 style="font-size:1rem;margin:0;">Recent Workout Sessions</h5>
                <a href="{{ route('member.workouts') }}" style="font-size:.8rem;color:var(--m-accent);text-decoration:none;">See all →</a>
            </div>
            @forelse($recentSessions as $session)
            <a href="{{ route('member.workout.show', $session) }}" style="text-decoration:none;color:inherit;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;padding:13px 0;border-bottom:1px solid var(--m-border);transition:background .12s;" onmouseover="this.style.background='#f8faff'" onmouseout="this.style.background=''">
                    <div style="display:flex;gap:12px;align-items:center;">
                        <div style="width:40px;height:40px;border-radius:10px;background:#f0f4ff;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                            {{ match(strtolower($session->session_type ?? '')) {
                                'strength', 'weights' => '🏋️',
                                'cardio'  => '🏃',
                                'yoga'    => '🧘',
                                'hiit'    => '⚡',
                                default   => '💪'
                            } }}
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:.9rem;">{{ $session->session_type ?? 'Workout Session' }}</div>
                            <div style="font-size:.75rem;color:var(--m-muted);">
                                {{ $session->trainer->name }}
                                @if($session->duration_mins) · {{ $session->duration_mins }} mins @endif
                                · {{ $session->exercises->count() }} exercises
                            </div>
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div style="font-size:.78rem;color:var(--m-muted);">{{ $session->session_date->format('d M') }}</div>
                        @if(!$session->member_feedback)
                            <span style="font-size:.68rem;background:#fef3c7;color:#92400e;padding:2px 7px;border-radius:10px;">Add feedback</span>
                        @else
                            <span style="font-size:.68rem;background:#dcfce7;color:#15803d;padding:2px 7px;border-radius:10px;">Reviewed ✓</span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div style="text-align:center;padding:30px;color:var(--m-muted);">
                <i class="bi bi-activity" style="font-size:2rem;display:block;margin-bottom:8px;opacity:.4;"></i>
                No workout sessions logged yet.
            </div>
            @endforelse
        </div>
    </div>

    {{-- Recent attendance + member info --}}
    <div class="col-12 col-xl-5">
        <div class="m-card mb-3">
            <h5 style="font-size:1rem;margin-bottom:16px;">Recent Check-Ins</h5>
            @forelse($recentAtt as $att)
            <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--m-border);font-size:.875rem;">
                <span>{{ \Carbon\Carbon::parse($att->check_in_time)->format('D, d M') }}</span>
                <span style="color:var(--m-muted);">{{ \Carbon\Carbon::parse($att->check_in_time)->format('g:i A') }}</span>
            </div>
            @empty
            <p style="color:var(--m-muted);font-size:.875rem;text-align:center;padding:16px 0;margin:0;">No check-ins yet.</p>
            @endforelse
            <div style="margin-top:12px;">
                <a href="{{ route('member.attendance') }}" class="m-btn-outline" style="width:100%;justify-content:center;font-size:.8rem;">View Full History</a>
            </div>
        </div>

        <div class="m-card">
            <h5 style="font-size:.85rem;margin-bottom:14px;color:var(--m-muted);text-transform:uppercase;letter-spacing:.05em;">My Details</h5>
            <div style="font-size:.875rem;display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--m-muted);">Member #</span>
                    <span style="font-family:monospace;font-weight:600;">{{ $member->member_number }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--m-muted);">Phone</span>
                    <strong>{{ $member->phone }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--m-muted);">Joined</span>
                    <strong>{{ $member->join_date->format('d M Y') }}</strong>
                </div>
                @if($member->trainer)
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--m-muted);">Trainer</span>
                    <strong>{{ $member->trainer->name }}</strong>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
