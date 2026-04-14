@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- KPI Row --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#dcfce7;color:#15803d;"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="kpi-value">{{ number_format($kpis['active_members']) }}</div>
                <div class="kpi-label">Active Members</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#dbeafe;color:#1d4ed8;"><i class="bi bi-door-open-fill"></i></div>
            <div>
                <div class="kpi-value">{{ number_format($kpis['today_checkins']) }}</div>
                <div class="kpi-label">Today's Check-Ins</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#fef9c3;color:#854d0e;"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="kpi-value">KES {{ number_format($kpis['monthly_revenue']) }}</div>
                <div class="kpi-label">Revenue This Month</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-calendar-x"></i></div>
            <div>
                <div class="kpi-value">{{ number_format($kpis['expiring_soon']) }}</div>
                <div class="kpi-label">Expiring in 7 Days</div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
        <div class="ft-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <h5 style="margin:0;font-size:1rem;">Monthly Revenue</h5>
                <span style="font-size:.78rem;color:var(--ft-muted);">Last 6 months</span>
            </div>
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="ft-card">
            <div style="margin-bottom:20px;">
                <h5 style="margin:0;font-size:1rem;">Member Status</h5>
            </div>
            <canvas id="memberChart" height="220"></canvas>
        </div>
    </div>
</div>

{{-- Attendance + Quick Actions --}}
<div class="row g-3">
    <div class="col-12 col-xl-8">
        <div class="ft-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <h5 style="margin:0;font-size:1rem;">Daily Attendance (14 days)</h5>
                <a href="{{ route('attendance.index') }}" style="font-size:.8rem;color:var(--ft-accent);text-decoration:none;">View all →</a>
            </div>
            <canvas id="attendanceChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="ft-card">
            <h5 style="font-size:1rem;margin-bottom:18px;">Quick Actions</h5>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <a href="{{ route('attendance.checkin') }}" class="btn-ft-primary" style="justify-content:center;">
                    <i class="bi bi-door-open"></i> Member Check-In
                </a>
                <a href="{{ route('members.create') }}" class="btn-ft-secondary" style="justify-content:center;">
                    <i class="bi bi-person-plus"></i> Add New Member
                </a>
                <a href="{{ route('payments.create') }}" class="btn-ft-secondary" style="justify-content:center;">
                    <i class="bi bi-cash-stack"></i> Record Payment
                </a>
                <a href="{{ route('reports.revenue') }}" class="btn-ft-secondary" style="justify-content:center;">
                    <i class="bi bi-bar-chart"></i> Revenue Report
                </a>
            </div>
        </div>

        <div class="ft-card mt-3">
            <h5 style="font-size:.9rem;margin-bottom:14px;color:var(--ft-muted);text-transform:uppercase;letter-spacing:.05em;">Summary</h5>
            <div style="display:flex;flex-direction:column;gap:8px;font-size:.875rem;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--ft-muted);">Total Members</span>
                    <strong>{{ $kpis['total_members'] }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--ft-muted);">Inactive Members</span>
                    <strong>{{ $kpis['inactive_members'] }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--ft-muted);">Expiring Soon</span>
                    <strong style="color:#dc2626;">{{ $kpis['expiring_soon'] }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const accent  = '#e94560';
const accent2 = '#0f3460';
const muted   = '#94a3b8';

// Revenue bar chart
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: @json($revenue['labels']),
        datasets: [{
            label: 'Revenue (KES)',
            data: @json($revenue['data']),
            backgroundColor: accent + 'cc',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: '#f1f5f9' }, ticks: { color: muted, callback: v => 'KES ' + v.toLocaleString() } },
            x: { grid: { display: false }, ticks: { color: muted } }
        }
    }
});

// Member status doughnut
const memberData = @json($members);
new Chart(document.getElementById('memberChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(memberData).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
        datasets: [{
            data: Object.values(memberData),
            backgroundColor: ['#dcfce7', '#fee2e2', '#fef3c7'],
            borderColor:     ['#15803d', '#dc2626', '#d97706'],
            borderWidth: 2,
            hoverOffset: 6,
        }]
    },
    options: {
        cutout: '65%',
        plugins: { legend: { position: 'bottom', labels: { color: muted, padding: 16, font: { size: 12 } } } }
    }
});

// Attendance line chart
new Chart(document.getElementById('attendanceChart'), {
    type: 'line',
    data: {
        labels: @json($attendance['labels']),
        datasets: [{
            label: 'Check-Ins',
            data: @json($attendance['data']),
            borderColor: accent2,
            backgroundColor: accent2 + '18',
            borderWidth: 2,
            pointRadius: 3,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: '#f1f5f9' }, ticks: { color: muted, stepSize: 1 } },
            x: { grid: { display: false }, ticks: { color: muted, maxTicksLimit: 7 } }
        }
    }
});
</script>
@endpush
