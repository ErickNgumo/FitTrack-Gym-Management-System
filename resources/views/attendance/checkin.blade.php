@extends('layouts.app')
@section('title', 'Member Check-In')
@section('page-title', 'Member Check-In')

@section('content')

<div style="max-width:560px;margin:0 auto;padding-top:20px;">

    <div class="ft-card" style="text-align:center;padding:44px 40px;">
        <div style="width:64px;height:64px;border-radius:16px;background:var(--ft-accent);display:flex;align-items:center;justify-content:center;font-size:1.8rem;color:#fff;margin:0 auto 20px;">
            <i class="bi bi-door-open-fill"></i>
        </div>
        <h4 style="font-family:'Sora',sans-serif;font-size:1.3rem;margin-bottom:6px;">Member Check-In</h4>
        <p style="color:var(--ft-muted);font-size:.875rem;margin-bottom:30px;">
            Enter member number or phone number.<br>Subscription status is validated automatically.
        </p>

        @if(session('success'))
            <div class="ft-alert ft-alert-success" style="text-align:left;">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="ft-alert ft-alert-danger" style="text-align:left;">
                <i class="bi bi-x-circle-fill"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('attendance.store') }}">
            @csrf
            <div style="margin-bottom:20px;text-align:left;">
                <label class="ft-label">Member Number or Phone</label>
                <input class="ft-input" name="identifier" id="identifier"
                       placeholder="e.g. FT-000001 or 07XXXXXXXX"
                       style="font-size:1.05rem;padding:12px 16px;text-align:center;letter-spacing:.04em;"
                       autofocus required>
            </div>
            <button type="submit" class="btn-ft-primary" style="width:100%;justify-content:center;padding:13px;font-size:1rem;">
                <i class="bi bi-box-arrow-in-right"></i> Check In
            </button>
        </form>
    </div>

    {{-- Today's check-ins mini table --}}
    <div class="ft-card" style="margin-top:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <h5 style="font-size:.95rem;margin:0;">Today's Check-Ins</h5>
            <a href="{{ route('attendance.index') }}" style="font-size:.78rem;color:var(--ft-accent);text-decoration:none;">View all →</a>
        </div>

        @php
            $todayRecords = \App\Models\Attendance::with('member')
                ->whereDate('check_in_time', today())
                ->orderByDesc('check_in_time')
                ->take(8)
                ->get();
        @endphp

        @forelse($todayRecords as $r)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid var(--ft-border);font-size:.875rem;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;border-radius:50%;background:var(--ft-accent);display:flex;align-items:center;justify-content:center;font-size:.8rem;color:#fff;font-weight:600;flex-shrink:0;">
                    {{ strtoupper(substr($r->member->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:500;">{{ $r->member->name }}</div>
                    <div style="font-size:.72rem;color:var(--ft-muted);">{{ $r->member->member_number }}</div>
                </div>
            </div>
            <span style="font-size:.78rem;color:var(--ft-muted);">{{ \Carbon\Carbon::parse($r->check_in_time)->format('g:i A') }}</span>
        </div>
        @empty
        <p style="color:var(--ft-muted);font-size:.875rem;text-align:center;padding:16px 0;margin:0;">No check-ins yet today.</p>
        @endforelse
    </div>

</div>

@endsection
