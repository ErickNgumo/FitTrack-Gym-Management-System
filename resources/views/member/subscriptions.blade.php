@extends('member.layouts.app')
@section('title', 'My Membership')
@section('page-title', 'Membership')

@section('content')

<div style="margin-bottom:20px;">
    <h4 style="font-size:1.15rem;margin:0;">Membership History</h4>
    <p style="font-size:.82rem;color:var(--m-muted);margin:0;">All subscription periods for your account</p>
</div>

@foreach($subs as $sub)
<div class="m-card mb-3">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div>
            <div style="font-weight:600;font-size:1rem;">{{ $sub->plan->name }}</div>
            <div style="font-size:.8rem;color:var(--m-muted);margin-top:3px;">
                {{ $sub->start_date->format('d M Y') }} → {{ $sub->end_date->format('d M Y') }}
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            @if($sub->status === 'active' && $sub->end_date->isFuture())
                <span class="m-badge m-badge-green">Active</span>
                <div style="font-size:.8rem;color:var(--m-muted);">{{ $sub->end_date->diffInDays(now()) }} days left</div>
            @elseif($sub->status === 'cancelled')
                <span class="m-badge m-badge-gray">Cancelled</span>
            @else
                <span class="m-badge m-badge-red">Expired</span>
            @endif
        </div>
    </div>

    {{-- Progress bar for active sub --}}
    @if($sub->status === 'active' && $sub->end_date->isFuture())
        @php
            $total   = $sub->start_date->diffInDays($sub->end_date);
            $elapsed = $sub->start_date->diffInDays(now());
            $pct     = $total > 0 ? min(100, round(($elapsed / $total) * 100)) : 0;
        @endphp
        <div style="margin-top:14px;">
            <div style="display:flex;justify-content:space-between;font-size:.72rem;color:var(--m-muted);margin-bottom:5px;">
                <span>{{ $elapsed }} of {{ $total }} days used</span>
                <span>{{ $pct }}%</span>
            </div>
            <div style="background:#f1f5f9;border-radius:6px;height:6px;overflow:hidden;">
                <div style="background:{{ $pct > 80 ? '#e94560' : '#0f3460' }};height:100%;width:{{ $pct }}%;border-radius:6px;transition:width .3s;"></div>
            </div>
        </div>
    @endif
</div>
@endforeach

@if($subs->isEmpty())
<div class="m-card" style="text-align:center;padding:60px 40px;color:var(--m-muted);">
    <i class="bi bi-card-checklist" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:.4;"></i>
    <p style="margin:0;">No membership records yet. Visit the front desk to subscribe.</p>
</div>
@endif

@endsection
