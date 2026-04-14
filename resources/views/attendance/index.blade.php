@extends('layouts.app')
@section('title', 'Attendance')
@section('page-title', 'Attendance Log')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;flex-wrap:wrap;gap:12px;">
    <div>
        <h4 style="margin:0;font-size:1.15rem;">Attendance Records</h4>
        <p style="margin:0;font-size:.8rem;color:var(--ft-muted);">{{ $todayCount }} check-ins today</p>
    </div>
    <a href="{{ route('attendance.checkin') }}" class="btn-ft-primary">
        <i class="bi bi-door-open"></i> Check-In Station
    </a>
</div>

<div class="ft-card mb-3">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div style="flex:1;min-width:180px;">
            <label class="ft-label">Search Member</label>
            <input class="ft-input" name="search" value="{{ request('search') }}" placeholder="Name or member number">
        </div>
        <div style="min-width:160px;">
            <label class="ft-label">Date</label>
            <input class="ft-input" name="date" type="date" value="{{ request('date') }}">
        </div>
        <div>
            <button type="submit" class="btn-ft-primary">Filter</button>
            <a href="{{ route('attendance.index') }}" class="btn-ft-secondary" style="margin-left:6px;">Clear</a>
        </div>
    </form>
</div>

<div class="ft-card" style="padding:0;overflow:hidden;">
    <table class="ft-table">
        <thead>
            <tr>
                <th>Member</th>
                <th>Date</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $r)
            <tr>
                <td>
                    <a href="{{ route('members.show', $r->member) }}" style="font-weight:500;text-decoration:none;color:var(--ft-text);">
                        {{ $r->member->name }}
                    </a>
                    <div style="font-size:.72rem;color:var(--ft-muted);">{{ $r->member->member_number }}</div>
                </td>
                <td style="font-size:.875rem;">{{ \Carbon\Carbon::parse($r->check_in_time)->format('D, d M Y') }}</td>
                <td style="font-size:.875rem;font-weight:500;">{{ \Carbon\Carbon::parse($r->check_in_time)->format('g:i A') }}</td>
                <td style="font-size:.875rem;">
                    @if($r->check_out_time)
                        {{ \Carbon\Carbon::parse($r->check_out_time)->format('g:i A') }}
                    @else
                        <span style="color:var(--ft-muted);font-size:.78rem;">Still in</span>
                    @endif
                </td>
                <td style="font-size:.875rem;color:var(--ft-muted);">
                    @if($r->check_out_time)
                        {{ \Carbon\Carbon::parse($r->check_in_time)->diffForHumans(\Carbon\Carbon::parse($r->check_out_time), true) }}
                    @else
                        —
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:40px;color:var(--ft-muted);">
                    <i class="bi bi-calendar-x" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                    No attendance records found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($records->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--ft-border);">{{ $records->links() }}</div>
    @endif
</div>

@endsection
