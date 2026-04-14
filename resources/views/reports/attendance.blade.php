@extends('layouts.app')
@section('title', 'Attendance Report')
@section('page-title', 'Attendance Report')

@section('content')

<div style="margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
    <a href="{{ route('reports.index') }}" style="font-size:.85rem;color:var(--ft-muted);text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Back to Reports
    </a>
    <form method="GET" style="display:flex;gap:10px;align-items:center;">
        <label class="ft-label" style="margin:0;">Show last</label>
        <select class="ft-input" name="days" style="padding:7px 12px;width:auto;" onchange="this.form.submit()">
            @foreach([7, 14, 30, 60, 90] as $d)
                <option value="{{ $d }}" {{ $days == $d ? 'selected' : '' }}>{{ $d }} days</option>
            @endforeach
        </select>
    </form>
</div>

<div class="ft-card mb-3">
    <h5 style="font-size:1rem;margin-bottom:20px;">Daily Check-Ins (last {{ $days }} days)</h5>
    <canvas id="attChart" height="80"></canvas>
</div>

<div class="ft-card" style="padding:0;overflow:hidden;">
    <table class="ft-table">
        <thead>
            <tr><th>Date</th><th style="text-align:right;">Check-Ins</th></tr>
        </thead>
        <tbody>
            @foreach(array_combine($attendance['labels'], $attendance['data']) as $date => $count)
            <tr>
                <td>{{ \Carbon\Carbon::parse($date)->format('D, d M Y') }}</td>
                <td style="text-align:right;font-weight:600;">{{ $count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
new Chart(document.getElementById('attChart'), {
    type: 'line',
    data: {
        labels: @json($attendance['labels']),
        datasets: [{
            label: 'Check-Ins',
            data: @json($attendance['data']),
            borderColor: '#0f3460',
            backgroundColor: '#0f346018',
            borderWidth: 2,
            pointRadius: 3,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: '#f1f5f9' }, beginAtZero: true, ticks: { color: '#94a3b8', stepSize: 1 } },
            x: { grid: { display: false }, ticks: { color: '#94a3b8', maxTicksLimit: 10 } }
        }
    }
});
</script>
@endpush

@endsection
