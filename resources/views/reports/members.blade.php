@extends('layouts.app')
@section('title', 'Members Report')
@section('page-title', 'Members Report')

@section('content')

<div style="margin-bottom:16px;">
    <a href="{{ route('reports.index') }}" style="font-size:.85rem;color:var(--ft-muted);text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Back to Reports
    </a>
</div>

<div class="row g-3 mb-3">
    @foreach(['active' => ['#dcfce7','#15803d'], 'inactive' => ['#f1f5f9','#64748b'], 'suspended' => ['#fef3c7','#d97706']] as $status => $colors)
    <div class="col-12 col-sm-4">
        <div class="kpi-card">
            <div class="kpi-icon" style="background:{{ $colors[0] }};color:{{ $colors[1] }};"><i class="bi bi-person"></i></div>
            <div>
                <div class="kpi-value">{{ $summary[$status] ?? 0 }}</div>
                <div class="kpi-label">{{ ucfirst($status) }} Members</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="ft-card" style="padding:0;overflow:hidden;">
    <div style="padding:18px 20px;border-bottom:1px solid var(--ft-border);">
        <h5 style="margin:0;font-size:1rem;">Expired Memberships</h5>
    </div>
    <table class="ft-table">
        <thead>
            <tr>
                <th>Member</th>
                <th>Plan</th>
                <th>Expired</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($expiring as $sub)
            <tr>
                <td>
                    <a href="{{ route('members.show', $sub->member) }}" style="font-weight:500;text-decoration:none;color:var(--ft-text);">{{ $sub->member->name }}</a>
                    <div style="font-size:.72rem;color:var(--ft-muted);">{{ $sub->member->phone }}</div>
                </td>
                <td style="font-size:.875rem;">{{ $sub->plan->name }}</td>
                <td style="font-size:.875rem;color:#dc2626;">{{ $sub->end_date->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('subscriptions.create', $sub->member) }}" class="btn-ft-primary" style="font-size:.78rem;padding:5px 10px;">Renew</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--ft-muted);">No expired memberships found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($expiring->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--ft-border);">{{ $expiring->links() }}</div>
    @endif
</div>

@endsection
