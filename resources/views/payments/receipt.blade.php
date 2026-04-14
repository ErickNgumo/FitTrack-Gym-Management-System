@extends('layouts.app')
@section('title', 'Receipt ' . $payment->receipt_number)
@section('page-title', 'Payment Receipt')

@push('styles')
<style>
    @media print {
        #sidebar, #topbar, .no-print { display: none !important; }
        #main-content { margin: 0 !important; padding: 20px !important; }
        .receipt-card { box-shadow: none !important; border: 1px solid #ddd !important; }
    }
</style>
@endpush

@section('content')

<div style="max-width:540px;margin:0 auto;">
    <div style="margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;" class="no-print">
        <a href="{{ route('members.show', $payment->member) }}" style="font-size:.85rem;color:var(--ft-muted);text-decoration:none;">
            <i class="bi bi-arrow-left"></i> Back to Member
        </a>
        <button onclick="window.print()" class="btn-ft-primary" style="font-size:.8rem;padding:6px 14px;">
            <i class="bi bi-printer"></i> Print Receipt
        </button>
    </div>

    <div class="ft-card receipt-card" style="padding:40px;">

        {{-- Header --}}
        <div style="text-align:center;border-bottom:2px solid var(--ft-border);padding-bottom:24px;margin-bottom:24px;">
            <div style="width:48px;height:48px;background:var(--ft-accent);border-radius:12px;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-size:1.3rem;color:#fff;font-weight:700;margin:0 auto 12px;">F</div>
            <h3 style="font-family:'Sora',sans-serif;margin-bottom:2px;font-size:1.2rem;">FitTrack Gym</h3>
            <div style="font-size:.78rem;color:var(--ft-muted);">OFFICIAL PAYMENT RECEIPT</div>
        </div>

        {{-- Receipt meta --}}
        <div style="display:flex;justify-content:space-between;margin-bottom:24px;font-size:.85rem;">
            <div>
                <div style="color:var(--ft-muted);margin-bottom:3px;">Receipt No.</div>
                <div style="font-family:monospace;font-weight:600;">{{ $payment->receipt_number }}</div>
            </div>
            <div style="text-align:right;">
                <div style="color:var(--ft-muted);margin-bottom:3px;">Date</div>
                <div style="font-weight:600;">{{ $payment->payment_date->format('d M Y') }}</div>
            </div>
        </div>

        {{-- Member info --}}
        <div style="background:var(--ft-bg);border-radius:10px;padding:16px;margin-bottom:24px;">
            <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:var(--ft-muted);margin-bottom:8px;">Received From</div>
            <div style="font-weight:600;font-size:1rem;">{{ $payment->member->name }}</div>
            <div style="font-size:.8rem;color:var(--ft-muted);">{{ $payment->member->member_number }} &nbsp;·&nbsp; {{ $payment->member->phone }}</div>
        </div>

        {{-- Line items --}}
        <table style="width:100%;border-collapse:collapse;font-size:.875rem;margin-bottom:24px;">
            <tr style="border-bottom:1px solid var(--ft-border);">
                <th style="text-align:left;padding:8px 0;font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:var(--ft-muted);">Description</th>
                <th style="text-align:right;padding:8px 0;font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:var(--ft-muted);">Amount</th>
            </tr>
            <tr>
                <td style="padding:12px 0;">
                    {{ $payment->subscription?->plan->name ?? 'Gym Payment' }}
                    @if($payment->coverage_start)
                        <div style="font-size:.75rem;color:var(--ft-muted);">
                            Coverage: {{ $payment->coverage_start->format('d M Y') }} – {{ $payment->coverage_end?->format('d M Y') }}
                        </div>
                    @endif
                </td>
                <td style="text-align:right;padding:12px 0;font-weight:600;">KES {{ number_format($payment->amount, 2) }}</td>
            </tr>
            <tr style="border-top:2px solid var(--ft-border);">
                <td style="padding:12px 0;font-weight:700;">TOTAL PAID</td>
                <td style="text-align:right;padding:12px 0;font-weight:700;font-size:1.1rem;color:var(--ft-accent);">KES {{ number_format($payment->amount, 2) }}</td>
            </tr>
        </table>

        {{-- Payment method --}}
        <div style="display:flex;justify-content:space-between;font-size:.85rem;margin-bottom:20px;">
            <div>
                <div style="color:var(--ft-muted);margin-bottom:3px;">Payment Method</div>
                <div style="font-weight:600;">{{ ucwords(str_replace('_',' ',$payment->payment_method)) }}</div>
            </div>
            @if($payment->mpesa_ref)
            <div style="text-align:right;">
                <div style="color:var(--ft-muted);margin-bottom:3px;">M-Pesa Ref</div>
                <div style="font-family:monospace;font-weight:600;">{{ $payment->mpesa_ref }}</div>
            </div>
            @endif
            <div style="text-align:right;">
                <div style="color:var(--ft-muted);margin-bottom:3px;">Recorded By</div>
                <div style="font-weight:600;">{{ $payment->recordedBy->name }}</div>
            </div>
        </div>

        {{-- Footer --}}
        <div style="text-align:center;border-top:1px solid var(--ft-border);padding-top:16px;font-size:.75rem;color:var(--ft-muted);">
            Thank you for your payment. This is an official receipt from FitTrack Gym.<br>
            Keep this receipt for your records.
        </div>
    </div>
</div>

@endsection
