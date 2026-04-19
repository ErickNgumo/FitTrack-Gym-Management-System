@extends('member.layouts.app')
@section('title', 'My Attendance')
@section('page-title', 'Attendance History')

@section('content')

<div style="margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;">
    <div>
        <h4 style="font-size:1.15rem;margin:0;">Attendance History</h4>
        <p style="font-size:.82rem;color:var(--m-muted);margin:0;">{{ $records->total() }} total check-ins</p>
    </div>
</div>

<div class="m-card" style="padding:0;overflow:hidden;">
    <table class="m-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $r)
            <tr>
                <td>{{ \Carbon\Carbon::parse($r->check_in_time)->format('D, d M Y') }}</td>
                <td style="font-weight:500;">{{ \Carbon\Carbon::parse($r->check_in_time)->format('g:i A') }}</td>
                <td style="color:var(--m-muted);">
                    {{ $r->check_out_time ? \Carbon\Carbon::parse($r->check_out_time)->format('g:i A') : '—' }}
                </td>
                <td style="color:var(--m-muted);">
                    @if($r->check_out_time)
                        {{ \Carbon\Carbon::parse($r->check_in_time)->diffForHumans(\Carbon\Carbon::parse($r->check_out_time), true) }}
                    @else
                        —
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;padding:40px;color:var(--m-muted);">No check-ins recorded yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($records->hasPages())
    <div style="padding:14px 18px;border-top:1px solid var(--m-border);">{{ $records->links() }}</div>
    @endif
</div>

@endsection
