@extends('layouts.app')
@section('title', $member->name)
@section('page-title', $member->name)

@section('content')

<div style="margin-bottom:16px;">
    <a href="{{ route('members.index') }}" style="font-size:.85rem;color:var(--ft-muted);text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Back to Members
    </a>
</div>

<div class="row g-3">

    {{-- Left: member card + quick info --}}
    <div class="col-12 col-xl-4">
        <div class="ft-card" style="text-align:center;padding:32px 24px;">
            <div style="width:72px;height:72px;border-radius:50%;background:var(--ft-accent);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-size:1.6rem;color:#fff;font-weight:700;margin:0 auto 16px;">
                {{ strtoupper(substr($member->name, 0, 1)) }}
            </div>
            <h4 style="margin-bottom:4px;font-size:1.1rem;">{{ $member->name }}</h4>
            <div style="font-size:.8rem;font-family:monospace;color:var(--ft-muted);margin-bottom:12px;">{{ $member->member_number }}</div>
            <span class="ft-badge badge-{{ $member->status }}">{{ ucfirst($member->status) }}</span>

            <div style="margin-top:22px;text-align:left;display:flex;flex-direction:column;gap:10px;font-size:.875rem;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--ft-muted);">Phone</span><strong>{{ $member->phone }}</strong>
                </div>
                @if($member->email)
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--ft-muted);">Email</span><strong>{{ $member->email }}</strong>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--ft-muted);">Joined</span><strong>{{ $member->join_date->format('d M Y') }}</strong>
                </div>
                @if($member->trainer)
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--ft-muted);">Trainer</span><strong>{{ $member->trainer->name }}</strong>
                </div>
                @endif
                @if($member->address)
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--ft-muted);">Address</span><strong>{{ $member->address }}</strong>
                </div>
                @endif
            </div>

            <div style="margin-top:20px;display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                <a href="{{ route('members.edit', $member) }}" class="btn-ft-secondary" style="font-size:.8rem;padding:6px 14px;">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('subscriptions.create', $member) }}" class="btn-ft-primary" style="font-size:.8rem;padding:6px 14px;">
                    <i class="bi bi-plus-lg"></i> New Sub
                </a>
            </div>
        </div>
    </div>

    {{-- Right: tabs --}}
    <div class="col-12 col-xl-8">

        {{-- Active Subscription Banner --}}
        @php $activeSub = $member->activeSubscription(); @endphp
        @if($activeSub)
            <div class="ft-alert ft-alert-success" style="margin-bottom:16px;">
                <i class="bi bi-check-circle-fill"></i>
                <div>
                    <strong>Active:</strong> {{ $activeSub->plan->name }}
                    &nbsp;·&nbsp; Expires <strong>{{ $activeSub->end_date->format('d M Y') }}</strong>
                    ({{ $activeSub->end_date->diffInDays(now()) }} days left)
                </div>
            </div>
        @else
            <div class="ft-alert ft-alert-warning" style="margin-bottom:16px;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                No active subscription.
                <a href="{{ route('subscriptions.create', $member) }}" style="color:inherit;font-weight:600;">Add one →</a>
            </div>
        @endif

        {{-- Subscriptions --}}
        <div class="ft-card mb-3">
            <h5 style="font-size:1rem;margin-bottom:16px;">Subscription History</h5>
            @forelse($member->subscriptions->sortByDesc('start_date') as $sub)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--ft-border);font-size:.875rem;">
                <div>
                    <strong>{{ $sub->plan->name }}</strong>
                    <div style="font-size:.75rem;color:var(--ft-muted);">
                        {{ $sub->start_date->format('d M Y') }} – {{ $sub->end_date->format('d M Y') }}
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="ft-badge badge-{{ $sub->status }}">{{ ucfirst($sub->status) }}</span>
                    @if($sub->status === 'active')
                    <form method="POST" action="{{ route('subscriptions.cancel', $sub) }}" onsubmit="return confirm('Cancel this subscription?')">
                        @csrf @method('PATCH')
                        <button type="submit" style="background:none;border:none;font-size:.75rem;color:#dc2626;cursor:pointer;">Cancel</button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
                <p style="color:var(--ft-muted);font-size:.875rem;">No subscriptions yet.</p>
            @endforelse
        </div>

        {{-- Payments --}}
        <div class="ft-card mb-3">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h5 style="font-size:1rem;margin:0;">Payments</h5>
                <a href="{{ route('payments.create', ['member_id' => $member->id]) }}" class="btn-ft-primary" style="font-size:.78rem;padding:5px 12px;">+ Record Payment</a>
            </div>
            @forelse($member->payments->sortByDesc('payment_date') as $payment)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--ft-border);font-size:.875rem;">
                <div>
                    <strong>KES {{ number_format($payment->amount, 2) }}</strong>
                    <span style="color:var(--ft-muted);margin-left:8px;">{{ ucfirst(str_replace('_',' ',$payment->payment_method)) }}</span>
                    <div style="font-size:.75rem;color:var(--ft-muted);">{{ $payment->receipt_number }}</div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:.78rem;color:var(--ft-muted);">{{ $payment->payment_date->format('d M Y') }}</span>
                    <a href="{{ route('payments.receipt', $payment) }}" style="font-size:.78rem;color:var(--ft-accent);">Receipt</a>
                </div>
            </div>
            @empty
                <p style="color:var(--ft-muted);font-size:.875rem;">No payments recorded.</p>
            @endforelse
        </div>

        {{-- Attendance --}}
        <div class="ft-card">
            <h5 style="font-size:1rem;margin-bottom:16px;">Recent Attendance</h5>
            @forelse($member->attendance->sortByDesc('check_in_time')->take(10) as $att)
            <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:8px 0;border-bottom:1px solid var(--ft-border);">
                <span>{{ \Carbon\Carbon::parse($att->check_in_time)->format('D, d M Y') }}</span>
                <span style="color:var(--ft-muted);">{{ \Carbon\Carbon::parse($att->check_in_time)->format('g:i A') }}</span>
            </div>
            @empty
                <p style="color:var(--ft-muted);font-size:.875rem;">No attendance records.</p>
            @endforelse
        </div>

    </div>
</div>

@endsection
