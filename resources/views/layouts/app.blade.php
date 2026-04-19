<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FitTrack') — FitTrack GMS</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Sora + Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --ft-primary:   #1a1a2e;
            --ft-accent:    #e94560;
            --ft-accent2:   #0f3460;
            --ft-sidebar-w: 250px;
            --ft-text:      #1a1a2e;
            --ft-muted:     #6c757d;
            --ft-bg:        #f4f6fb;
            --ft-card:      #ffffff;
            --ft-border:    #e3e8f0;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--ft-bg);
            color: var(--ft-text);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, .brand-name {
            font-family: 'Sora', sans-serif;
        }

        /* ── Sidebar ── */
        #sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--ft-sidebar-w);
            background: var(--ft-primary);
            z-index: 1000;
            overflow-y: auto;
            transition: transform .25s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 24px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-brand .logo-box {
            width: 38px; height: 38px;
            background: var(--ft-accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff; font-weight: 700;
        }

        .brand-name { color: #fff; font-size: 1.15rem; font-weight: 700; line-height: 1.2; }
        .brand-sub  { color: rgba(255,255,255,.4); font-size: .65rem; letter-spacing: .08em; text-transform: uppercase; }

        .sidebar-section {
            padding: 16px 14px 4px;
            color: rgba(255,255,255,.3);
            font-size: .65rem;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .nav-link-ft {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            color: rgba(255,255,255,.65);
            text-decoration: none;
            font-size: .875rem;
            border-radius: 0;
            transition: background .15s, color .15s;
        }

        .nav-link-ft:hover,
        .nav-link-ft.active {
            background: rgba(255,255,255,.08);
            color: #fff;
        }

        .nav-link-ft.active {
            border-left: 3px solid var(--ft-accent);
            padding-left: 17px;
        }

        .nav-link-ft i { font-size: 1rem; width: 20px; text-align: center; }

        /* ── Top bar ── */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--ft-sidebar-w);
            right: 0;
            height: 60px;
            background: #fff;
            border-bottom: 1px solid var(--ft-border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            z-index: 999;
            gap: 12px;
        }

        #topbar .page-title {
            font-family: 'Sora', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: var(--ft-text);
            margin: 0;
            flex: 1;
        }

        /* ── Main content ── */
        #main-content {
            margin-left: var(--ft-sidebar-w);
            margin-top: 60px;
            padding: 28px 28px 40px;
            min-height: calc(100vh - 60px);
        }

        /* ── Cards ── */
        .ft-card {
            background: var(--ft-card);
            border: 1px solid var(--ft-border);
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }

        /* ── KPI cards ── */
        .kpi-card {
            background: var(--ft-card);
            border: 1px solid var(--ft-border);
            border-radius: 14px;
            padding: 22px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .kpi-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .kpi-value {
            font-family: 'Sora', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1;
        }

        .kpi-label {
            font-size: .78rem;
            color: var(--ft-muted);
            margin-top: 3px;
        }

        /* ── Tables ── */
        .ft-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .875rem;
        }

        .ft-table thead th {
            background: var(--ft-bg);
            border-bottom: 1px solid var(--ft-border);
            padding: 10px 14px;
            font-weight: 600;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--ft-muted);
            white-space: nowrap;
        }

        .ft-table tbody tr {
            border-bottom: 1px solid var(--ft-border);
            transition: background .12s;
        }

        .ft-table tbody tr:hover { background: #f8f9fc; }

        .ft-table tbody td {
            padding: 11px 14px;
            vertical-align: middle;
        }

        /* ── Badges ── */
        .badge-active    { background: #dcfce7; color: #15803d; }
        .badge-inactive  { background: #f1f5f9; color: #64748b; }
        .badge-expired   { background: #fee2e2; color: #dc2626; }
        .badge-suspended { background: #fef3c7; color: #d97706; }
        .ft-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 600;
        }

        /* ── Buttons ── */
        .btn-ft-primary {
            background: var(--ft-accent);
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: opacity .15s;
        }

        .btn-ft-primary:hover { opacity: .88; color: #fff; }

        .btn-ft-secondary {
            background: transparent;
            border: 1px solid var(--ft-border);
            color: var(--ft-text);
            padding: 8px 18px;
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background .15s;
        }

        .btn-ft-secondary:hover { background: var(--ft-bg); color: var(--ft-text); }

        /* ── Form controls ── */
        .ft-label {
            font-size: .8rem;
            font-weight: 600;
            color: var(--ft-muted);
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 5px;
            display: block;
        }

        .ft-input {
            width: 100%;
            padding: 9px 13px;
            border: 1px solid var(--ft-border);
            border-radius: 8px;
            font-size: .875rem;
            background: #fff;
            color: var(--ft-text);
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }

        .ft-input:focus {
            border-color: var(--ft-accent);
            box-shadow: 0 0 0 3px rgba(233,69,96,.1);
        }

        /* ── Alerts ── */
        .ft-alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: .875rem;
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .ft-alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .ft-alert-danger   { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
        .ft-alert-warning  { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #topbar { left: 0; }
            #main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="logo-box">F</div>
        <div>
            <div class="brand-name">FitTrack</div>
            <div class="brand-sub">Gym Management</div>
        </div>
    </div>

    <div class="sidebar-section">Main</div>
    <a href="{{ route('dashboard') }}"        class="nav-link-ft {{ request()->routeIs('dashboard')       ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <a href="{{ route('attendance.checkin') }}" class="nav-link-ft {{ request()->routeIs('attendance.*') ? 'active' : '' }}"><i class="bi bi-door-open"></i> Check-In</a>

    <div class="sidebar-section">Members</div>
    <a href="{{ route('members.index') }}"     class="nav-link-ft {{ request()->routeIs('members.*')       ? 'active' : '' }}"><i class="bi bi-people"></i> Members</a>
    <a href="{{ route('trainers.index') }}"    class="nav-link-ft {{ request()->routeIs('trainers.*')      ? 'active' : '' }}"><i class="bi bi-person-badge"></i> Trainers</a>

    <div class="sidebar-section">Finance</div>
    <a href="{{ route('payments.index') }}"    class="nav-link-ft {{ request()->routeIs('payments.*')      ? 'active' : '' }}"><i class="bi bi-cash-stack"></i> Payments</a>

    <div class="sidebar-section">Insights</div>
    <a href="{{ route('reports.index') }}"     class="nav-link-ft {{ request()->routeIs('reports.*')       ? 'active' : '' }}"><i class="bi bi-bar-chart-line"></i> Reports</a>

    <div class="sidebar-section">Admin</div>
    <a href="{{ route('portal.members') }}"   class="nav-link-ft {{ request()->routeIs('portal.*')        ? 'active' : '' }}"><i class="bi bi-shield-lock"></i> Portal Access</a>

    <div style="padding: 24px 20px; margin-top: auto; border-top: 1px solid rgba(255,255,255,.08); position: absolute; bottom: 0; width: 100%;">
        <div style="color:rgba(255,255,255,.5); font-size:.78rem; margin-bottom:6px;">{{ auth()->user()->name }}</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none; border:none; color:rgba(255,255,255,.4); font-size:.8rem; padding:0; cursor:pointer;">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </div>
</nav>

{{-- ── Top bar ── --}}
<header id="topbar">
    <button id="sidebar-toggle" class="d-md-none btn btn-sm" style="border:none;background:none;font-size:1.2rem;"><i class="bi bi-list"></i></button>
    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
    <div style="display:flex; align-items:center; gap:14px;">
        <span style="font-size:.8rem; color:var(--ft-muted);">{{ now()->format('D, d M Y') }}</span>
        <div style="width:34px;height:34px;border-radius:50%;background:var(--ft-accent);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;font-size:.85rem;">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
    </div>
</header>

{{-- ── Main ── --}}
<main id="main-content">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="ft-alert ft-alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="ft-alert ft-alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('open');
    });
</script>
@stack('scripts')
</body>
</html>
