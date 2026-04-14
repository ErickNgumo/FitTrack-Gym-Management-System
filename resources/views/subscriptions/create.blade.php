@extends('layouts.app')
@section('title', 'New Subscription')
@section('page-title', 'New Subscription')

@section('content')
<div style="max-width:560px;">
    <div style="margin-bottom:16px;">
        <a href="{{ route('members.show', $member) }}" style="font-size:.85rem;color:var(--ft-muted);text-decoration:none;">
            <i class="bi bi-arrow-left"></i> Back to {{ $member->name }}
        </a>
    </div>
    <div class="ft-card">
        <h5 style="margin-bottom:6px;">New Subscription</h5>
        <p style="font-size:.875rem;color:var(--ft-muted);margin-bottom:24px;">
            For <strong>{{ $member->name }}</strong> ({{ $member->member_number }})
        </p>
        <form method="POST" action="{{ route('subscriptions.store', $member) }}">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="ft-label">Membership Plan *</label>
                    @foreach($plans as $plan)
                    <label style="display:flex;align-items:center;justify-content:space-between;border:1.5px solid var(--ft-border);border-radius:10px;padding:14px 16px;cursor:pointer;margin-bottom:10px;transition:border-color .15s;" class="plan-option">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <input type="radio" name="plan_id" value="{{ $plan->id }}" style="accent-color:var(--ft-accent);" {{ old('plan_id') == $plan->id ? 'checked' : '' }} required>
                            <div>
                                <div style="font-weight:600;font-size:.9rem;">{{ $plan->name }}</div>
                                <div style="font-size:.75rem;color:var(--ft-muted);">{{ $plan->duration_days }} days</div>
                            </div>
                        </div>
                        <div style="font-weight:700;font-size:1rem;">KES {{ number_format($plan->price, 0) }}</div>
                    </label>
                    @endforeach
                </div>
                <div class="col-12 col-md-6">
                    <label class="ft-label">Start Date</label>
                    <input class="ft-input" name="start_date" type="date" value="{{ old('start_date', date('Y-m-d')) }}">
                    <p style="font-size:.72rem;color:var(--ft-muted);margin-top:4px;">Leave blank to start today (or after current subscription ends).</p>
                </div>
            </div>
            <div style="margin-top:24px;display:flex;gap:10px;">
                <button type="submit" class="btn-ft-primary"><i class="bi bi-check-lg"></i> Create Subscription</button>
                <a href="{{ route('members.show', $member) }}" class="btn-ft-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
