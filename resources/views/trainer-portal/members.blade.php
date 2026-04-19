@extends('trainer-portal.layouts.app')
@section('title', 'My Members')
@section('page-title', 'My Members')

@section('content')

<div style="margin-bottom:20px;">
    <h4 style="font-size:1.15rem;margin:0;">Assigned Members</h4>
    <p style="font-size:.82rem;color:var(--t-muted);margin:0;">{{ $members->count() }} members under your guidance</p>
</div>

<div class="row g-3">
    @forelse($members as $member)
    @php $activeSub = $member->subscriptions->first(); @endphp
    <div class="col-12 col-md-6 col-xl-4">
        <div class="t-card" style="position:relative;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                <div style="width:46px;height:46px;border-radius:50%;background:var(--t-accent);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-size:1.1rem;color:#fff;font-weight:700;flex-shrink:0;">
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:.95rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $member->name }}</div>
                    <div style="font-size:.72rem;color:var(--t-muted);font-family:monospace;">{{ $member->member_number }}</div>
                </div>
                @if($activeSub)
                    <span class="t-badge t-badge-green">Active</span>
                @else
                    <span class="t-badge t-badge-red">Expired</span>
                @endif
            </div>

            <div style="font-size:.82rem;display:flex;flex-direction:column;gap:6px;margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:8px;color:var(--t-muted);">
                    <i class="bi bi-telephone"></i> {{ $member->phone }}
                </div>
                @if($activeSub)
                <div style="display:flex;align-items:center;gap:8px;color:var(--t-muted);">
                    <i class="bi bi-calendar-check"></i> Sub expires {{ $activeSub->end_date->format('d M Y') }}
                </div>
                @endif
                <div style="display:flex;align-items:center;gap:8px;color:var(--t-muted);">
                    <i class="bi bi-door-open"></i>
                    {{ $member->attendance()->whereMonth('check_in_time', now()->month)->count() }} visits this month
                </div>
            </div>

            <div style="display:flex;gap:8px;">
                <a href="{{ route('trainer.member.show', $member) }}" class="t-btn-primary" style="flex:1;justify-content:center;font-size:.82rem;padding:7px 12px;">
                    <i class="bi bi-person-lines-fill"></i> View Profile
                </a>
                <a href="{{ route('trainer.session.create', $member) }}" class="t-btn-outline" style="font-size:.82rem;padding:7px 12px;">
                    <i class="bi bi-plus-lg"></i> Log Session
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="t-card" style="text-align:center;padding:60px 40px;color:var(--t-muted);">
            <i class="bi bi-people" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:.4;"></i>
            <p style="margin:0;">No members have been assigned to you yet.<br>Ask the admin to assign members from the staff panel.</p>
        </div>
    </div>
    @endforelse
</div>

@endsection
