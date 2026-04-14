{{-- payments/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Payments')
@section('page-title', 'Payments')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;flex-wrap:wrap;gap:12px;">
    <div>
        <h4 style="margin:0;font-size:1.15rem;">Payment Records</h4>
        <p style="margin:0;font-size:.8rem;color:var(--ft-muted);">{{ $payments->total() }} total payments</p>
    </div>
    <a href="{{ route('payments.create') }}" class="btn-ft-primary">
        <i class="bi bi-plus-lg"></i> Record Payment
    </a>
</div>

<div class="ft-card mb-3">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div style="flex:1;min-width:180px;">
            <label class="ft-label">Search</label>
            <input class="ft-input" name="search" value="{{ request('search') }}" placeholder="Member name or receipt #">
        </div>
        <div style="min-width:150px;">
            <label class="ft-label">Method</label>
            <select class="ft-input" name="method" style="padding:9px 13px;">
                <option value="">All Methods</option>
                <option value="cash"          {{ request('method') === 'cash'          ? 'selected' : '' }}>Cash</option>
                <option value="mpesa"         {{ request('method') === 'mpesa'         ? 'selected' : '' }}>M-Pesa</option>
                <option value="bank_transfer" {{ request('method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="card"          {{ request('method') === 'card'          ? 'selected' : '' }}>Card</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn-ft-primary">Filter</button>
            <a href="{{ route('payments.index') }}" class="btn-ft-secondary" style="margin-left:6px;">Clear</a>
        </div>
    </form>
</div>

<div class="ft-card" style="padding:0;overflow:hidden;">
    <table class="ft-table">
        <thead>
            <tr>
                <th>Receipt #</th>
                <th>Member</th>
                <th>Plan</th>
                <th>Method</th>
                <th>Date</th>
                <th style="text-align:right;">Amount</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $p)
            <tr>
                <td><span style="font-family:monospace;font-size:.78rem;color:var(--ft-muted);">{{ $p->receipt_number }}</span></td>
                <td>
                    <a href="{{ route('members.show', $p->member) }}" style="font-weight:500;text-decoration:none;color:var(--ft-text);">{{ $p->member->name }}</a>
                    <div style="font-size:.72rem;color:var(--ft-muted);">{{ $p->member->member_number }}</div>
                </td>
                <td style="font-size:.875rem;">{{ $p->subscription?->plan->name ?? '—' }}</td>
                <td>
                    <span style="font-size:.8rem;background:#f1f5f9;padding:3px 8px;border-radius:6px;">
                        {{ ucwords(str_replace('_',' ',$p->payment_method)) }}
                    </span>
                </td>
                <td style="font-size:.875rem;">{{ $p->payment_date->format('d M Y') }}</td>
                <td style="text-align:right;font-weight:600;">KES {{ number_format($p->amount, 2) }}</td>
                <td>
                    <a href="{{ route('payments.receipt', $p) }}" class="btn-ft-secondary" style="padding:5px 10px;font-size:.78rem;">
                        <i class="bi bi-receipt"></i> Receipt
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:40px;color:var(--ft-muted);">No payments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($payments->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--ft-border);">{{ $payments->links() }}</div>
    @endif
</div>

@endsection
