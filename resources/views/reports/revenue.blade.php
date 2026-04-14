@extends('layouts.app')
@section('title', 'Revenue Report')
@section('page-title', 'Revenue Report')

@section('content')

<div style="margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
    <a href="{{ route('reports.index') }}" style="font-size:.85rem;color:var(--ft-muted);text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Back to Reports
    </a>
    <form method="GET" style="display:flex;gap:10px;align-items:center;">
        <label class="ft-label" style="margin:0;">Show last</label>
        <select class="ft-input" name="months" style="padding:7px 12px;width:auto;" onchange="this.form.submit()">
            @foreach([3,6,12] as $m)
                <option value="{{ $m }}" {{ $months == $m ? 'selected' : '' }}>{{ $m }} months</option>
            @endforeach
        </select>
    </form>
</div>

<div class="ft-card mb-3">
    <h5 style="font-size:1rem;margin-bottom:20px;">Monthly Revenue (KES)</h5>
    <canvas id="revChart" height="80"></canvas>
</div>

<div class="ft-card" style="padding:0;overflow:hidden;">
    <table class="ft-table">
        <thead>
            <tr><th>Month</th><th style="text-align:right;">Revenue (KES)</th></tr>
        </thead>
        <tbody>
            @foreach(array_combine($revenue['labels'], $revenue['data']) as $label => $total)
            <tr>
                <td>{{ $label }}</td>
                <td style="text-align:right;font-weight:600;">{{ number_format($total, 2) }}</td>
            </tr>
            @endforeach
            <tr style="background:var(--ft-bg);">
                <td style="font-weight:700;">Total</td>
                <td style="text-align:right;font-weight:700;">{{ number_format(array_sum($revenue['data']), 2) }}</td>
            </tr>
        </tbody>
    </table>
</div>

@push('scripts')
<script>
new Chart(document.getElementById('revChart'), {
    type: 'bar',
    data: {
        labels: @json($revenue['labels']),
        datasets: [{
            label: 'Revenue (KES)',
            data: @json($revenue['data']),
            backgroundColor: '#e94560cc',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', callback: v => 'KES ' + v.toLocaleString() } },
            x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
        }
    }
});
</script>
@endpush

@endsection
