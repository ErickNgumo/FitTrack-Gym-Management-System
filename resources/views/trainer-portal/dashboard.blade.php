@extends('trainer-portal.layouts.app')
@section('title', 'Trainer Dashboard')
@section('page-title', 'My Dashboard')

@section('content')

<div style="margin-bottom:22px;">
    <h4 style="font-size:1.2rem;margin-bottom:2px;">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', $trainer->name)[0] }} 💪</h4>
    <p style="font-size:.85rem;color:var(--t-muted);margin:0;">Here's a snapshot of your squad today.</p>
</div>

{{-- KPIs --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="t-kpi">
            <div class="t-kpi-icon" style="background:#dcfce7;color:#15803d;"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="t-kpi-val">{{ $members->count() }}</div>
                <div class="t-kpi-label">Assigned Members</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="t-kpi">
            <div class="t-kpi-icon" style="background:#dbeafe;color:#1d4ed8;"><i class="bi bi-activity"></i></div>
            <div>
                <div class="t-kpi-val">{{ $todaySessions->count() }}</div>
                <div class="t-kpi-label">Today's Sessions</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="t-kpi">
            <div class="t-kpi-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="t-kpi-val">{{ $members->filter(fn($m) => $m->subscriptions->isNotEmpty())->count() }}</div>
                <div class="t-kpi-label">Active Subs</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="t-kpi">
            <div class="t-kpi-icon" style="background:#f3e8ff;color:#7c3aed;"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="t-kpi-val">{{ $recentSessions->count() }}</div>
                <div class="t-kpi-label">Recent Sessions</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- My Members list --}}
    <div class="col-12 col-xl-6">
        <div class="t-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
                <h5 style="font-size:1rem;margin:0;">My Members</h5>
                <a href="{{ route('trainer.members') }}" style="font-size:.8rem;color:var(--t-accent);text-decoration:none;">View all →</a>
            </div>
            @forelse($members->take(6) as $member)
            <a href="{{ route('trainer.member.show', $member) }}" style="text-decoration:none;color:inherit;">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:11px 0;border-bottom:1px solid var(--t-border);" onmouseover="this.style.background='#f0faf5'" onmouseout="this.style.background=''">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:var(--t-accent);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;font-size:.85rem;flex-shrink:0;">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:500;font-size:.9rem;">{{ $member->name }}</div>
                            <div style="font-size:.73rem;color:var(--t-muted);">{{ $member->member_number }}</div>
                        </div>
                    </div>
                    @if($member->subscriptions->isNotEmpty())
                        <span class="t-badge t-badge-green">Active</span>
                    @else
                        <span class="t-badge t-badge-red">No Sub</span>
                    @endif
                </div>
            </a>
            @empty
            <p style="text-align:center;color:var(--t-muted);padding:30px 0;margin:0;font-size:.875rem;">No members assigned yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent sessions + today --}}
    <div class="col-12 col-xl-6">
        @if($todaySessions->isNotEmpty())
        <div class="t-card mb-3">
            <h5 style="font-size:.9rem;margin-bottom:14px;color:var(--t-accent);text-transform:uppercase;letter-spacing:.05em;">Today's Sessions</h5>
            @foreach($todaySessions as $s)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--t-border);font-size:.875rem;">
                <div>
                    <strong>{{ $s->member->name }}</strong>
                    <div style="font-size:.75rem;color:var(--t-muted);">{{ $s->session_type ?? 'Session' }} · {{ $s->duration_mins ?? '?' }} mins</div>
                </div>
                <a href="{{ route('trainer.session.show', $s) }}" style="font-size:.78rem;color:var(--t-accent);text-decoration:none;">View →</a>
            </div>
            @endforeach
        </div>
        @endif

        <div class="t-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h5 style="font-size:1rem;margin:0;">Recent Sessions</h5>
            </div>
            @forelse($recentSessions as $s)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--t-border);font-size:.875rem;">
                <div>
                    <div style="font-weight:500;">{{ $s->member->name }}</div>
                    <div style="font-size:.75rem;color:var(--t-muted);">{{ $s->session_type ?? 'Session' }} · {{ $s->session_date->format('d M') }}</div>
                </div>
                <a href="{{ route('trainer.session.show', $s) }}" style="font-size:.78rem;color:var(--t-accent);text-decoration:none;">View →</a>
            </div>
            @empty
            <p style="color:var(--t-muted);font-size:.875rem;text-align:center;padding:20px 0;margin:0;">No sessions logged yet.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
