@extends('member.layouts.app')
@section('title', 'My Payments')
@section('page-title', 'Payment History')

@section('content')

<div style="margin-bottom:20px;">
    <h4 style="font-size:1.15rem;margin:0;">Payment History</h4>
    <p style="font-size:.82rem;color:var(--m-muted);margin:0;">All payments recorded for your account</p>
</div>

<div class="m-card" style="padding:0;overflow:hidden;">
    <table class="m-table">
        <thead>
            <tr>
                <th>Receipt #</th>
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
                <td><span style="font-family:monospace;font-size:.78rem;color:var(--m-muted);">{{ $p->receipt_number }}</span></td>
                <td style="font-size:.875rem;">{{ $p->subscription?->plan->name ?? '—' }}</td>
                <td>
                    <span style="background:#f1f5f9;padding:3px 8px;border-radius:6px;font-size:.78rem;">
                        {{ ucwords(str_replace('_',' ',$p->payment_method)) }}
                    </span>
                </td>
                <td style="font-size:.875rem;">{{ $p->payment_date->format('d M Y') }}</td>
                <td style="text-align:right;font-weight:600;">KES {{ number_format($p->amount, 2) }}</td>
                <td>
                    <a href="{{ route('payments.receipt', $p) }}" target="_blank"
                       style="font-size:.78rem;color:var(--m-accent);text-decoration:none;">
                        <i class="bi bi-receipt"></i> Receipt
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:40px;color:var(--m-muted);">No payments recorded yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($payments->hasPages())
    <div style="padding:14px 18px;border-top:1px solid var(--m-border);">{{ $payments->links() }}</div>
    @endif
</div>

@endsection
