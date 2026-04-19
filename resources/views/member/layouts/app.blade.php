<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My FitTrack') — Member Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --m-primary: #0f3460;
            --m-accent:  #e94560;
            --m-bg:      #f0f4ff;
            --m-card:    #ffffff;
            --m-border:  #e2e8f8;
            --m-muted:   #64748b;
            --m-sidebar: 240px;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--m-bg); margin: 0; }
        h1,h2,h3,h4,h5 { font-family: 'Sora', sans-serif; }

        /* sidebar */
        #m-sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--m-sidebar);
            background: var(--m-primary);
            z-index: 100; overflow-y: auto;
            display: flex; flex-direction: column;
        }
        .m-brand {
            padding: 22px 18px 16px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; gap: 10px;
        }
        .m-brand-logo {
            width: 36px; height: 36px; border-radius: 9px;
            background: var(--m-accent);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif; font-weight: 700; color: #fff; font-size: 1rem;
        }
        .m-brand-text { color: #fff; font-size: 1rem; font-weight: 600; line-height: 1.2; }
        .m-brand-sub  { color: rgba(255,255,255,.35); font-size: .6rem; text-transform: uppercase; letter-spacing: .08em; }

        .m-nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 18px; color: rgba(255,255,255,.6);
            text-decoration: none; font-size: .875rem;
            transition: background .15s, color .15s;
        }
        .m-nav-link:hover, .m-nav-link.active {
            background: rgba(255,255,255,.08); color: #fff;
        }
        .m-nav-link.active { border-left: 3px solid var(--m-accent); padding-left: 15px; }
        .m-nav-link i { width: 18px; text-align: center; font-size: .95rem; }
        .m-nav-section {
            padding: 14px 18px 4px;
            color: rgba(255,255,255,.28);
            font-size: .62rem; letter-spacing: .1em; text-transform: uppercase;
        }
        .m-sidebar-footer {
            margin-top: auto;
            padding: 18px;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        /* topbar */
        #m-topbar {
            position: fixed; top: 0;
            left: var(--m-sidebar); right: 0; height: 58px;
            background: #fff; border-bottom: 1px solid var(--m-border);
            display: flex; align-items: center; padding: 0 22px;
            z-index: 99; gap: 12px;
        }
        .m-page-title { font-family: 'Sora', sans-serif; font-size: .95rem; font-weight: 600; flex: 1; margin: 0; }

        /* main */
        #m-main {
            margin-left: var(--m-sidebar);
            margin-top: 58px;
            padding: 26px 26px 48px;
        }

        /* cards */
        .m-card {
            background: var(--m-card);
            border: 1px solid var(--m-border);
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 1px 4px rgba(15,52,96,.04);
        }

        /* kpi */
        .m-kpi {
            background: var(--m-card);
            border: 1px solid var(--m-border);
            border-radius: 14px;
            padding: 20px;
            display: flex; align-items: center; gap: 14px;
        }
        .m-kpi-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; flex-shrink: 0;
        }
        .m-kpi-val   { font-family: 'Sora', sans-serif; font-size: 1.6rem; font-weight: 700; line-height: 1; }
        .m-kpi-label { font-size: .75rem; color: var(--m-muted); margin-top: 3px; }

        /* table */
        .m-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .m-table thead th {
            background: var(--m-bg); border-bottom: 1px solid var(--m-border);
            padding: 9px 13px; font-size: .72rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .05em; color: var(--m-muted);
        }
        .m-table tbody tr { border-bottom: 1px solid var(--m-border); transition: background .12s; }
        .m-table tbody tr:hover { background: #f8faff; }
        .m-table tbody td { padding: 10px 13px; vertical-align: middle; }

        /* badges */
        .m-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: .7rem; font-weight: 600; }
        .m-badge-green  { background: #dcfce7; color: #15803d; }
        .m-badge-red    { background: #fee2e2; color: #dc2626; }
        .m-badge-yellow { background: #fef3c7; color: #92400e; }
        .m-badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .m-badge-gray   { background: #f1f5f9; color: #64748b; }

        /* alerts */
        .m-alert { padding: 11px 15px; border-radius: 10px; font-size: .875rem; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 9px; }
        .m-alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .m-alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .m-alert-danger  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

        /* btn */
        .m-btn-primary {
            background: var(--m-accent); color: #fff; border: none;
            padding: 8px 16px; border-radius: 8px; font-size: .85rem; font-weight: 500;
            cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
            transition: opacity .15s;
        }
        .m-btn-primary:hover { opacity: .87; color: #fff; }
        .m-btn-outline {
            background: transparent; border: 1px solid var(--m-border); color: #1a1a2e;
            padding: 8px 16px; border-radius: 8px; font-size: .85rem; font-weight: 500;
            cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
            transition: background .12s;
        }
        .m-btn-outline:hover { background: var(--m-bg); color: #1a1a2e; }

        /* input */
        .m-label { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--m-muted); margin-bottom: 5px; display: block; }
        .m-input {
            width: 100%; padding: 9px 12px; border: 1.5px solid var(--m-border);
            border-radius: 8px; font-size: .875rem; outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .m-input:focus { border-color: var(--m-primary); box-shadow: 0 0 0 3px rgba(15,52,96,.1); }

        @media (max-width: 768px) {
            #m-sidebar { transform: translateX(-100%); }
            #m-sidebar.open { transform: translateX(0); }
            #m-topbar, #m-main { left: 0; margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<nav id="m-sidebar">
    <div class="m-brand">
        <div class="m-brand-logo">F</div>
        <div>
            <div class="m-brand-text">FitTrack</div>
            <div class="m-brand-sub">Member Portal</div>
        </div>
    </div>

    <div class="m-nav-section">Overview</div>
    <a href="{{ route('member.dashboard') }}"     class="m-nav-link {{ request()->routeIs('member.dashboard')    ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>

    <div class="m-nav-section">My Gym</div>
    <a href="{{ route('member.workouts') }}"      class="m-nav-link {{ request()->routeIs('member.workout*')     ? 'active' : '' }}"><i class="bi bi-activity"></i> Workouts</a>
    <a href="{{ route('member.attendance') }}"    class="m-nav-link {{ request()->routeIs('member.attendance')   ? 'active' : '' }}"><i class="bi bi-calendar-check"></i> Attendance</a>
    <a href="{{ route('member.subscriptions') }}" class="m-nav-link {{ request()->routeIs('member.subscriptions')? 'active' : '' }}"><i class="bi bi-card-checklist"></i> Membership</a>
    <a href="{{ route('member.payments') }}"      class="m-nav-link {{ request()->routeIs('member.payments')     ? 'active' : '' }}"><i class="bi bi-receipt"></i> Payments</a>

    <div class="m-sidebar-footer">
        @php $mc = Auth::guard('member')->user(); $mem = $mc?->member; @endphp
        @if($mem)
        <div style="color:rgba(255,255,255,.5);font-size:.78rem;margin-bottom:4px;">{{ $mem->name }}</div>
        <div style="color:rgba(255,255,255,.3);font-size:.7rem;margin-bottom:10px;font-family:monospace;">{{ $mem->member_number }}</div>
        @endif
        <form method="POST" action="{{ route('member.logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.4);font-size:.8rem;padding:0;cursor:pointer;">
                <i class="bi bi-box-arrow-left"></i> Sign Out
            </button>
        </form>
    </div>
</nav>

<header id="m-topbar">
    <h1 class="m-page-title">@yield('page-title', 'Dashboard')</h1>
    <div style="display:flex;align-items:center;gap:12px;">
        <span style="font-size:.78rem;color:var(--m-muted);">{{ now()->format('d M Y') }}</span>
        <div style="width:32px;height:32px;border-radius:50%;background:var(--m-primary);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;font-size:.8rem;">
            {{ strtoupper(substr($mem->name ?? 'M', 0, 1)) }}
        </div>
    </div>
</header>

<main id="m-main">
    @if(session('success'))
        <div class="m-alert m-alert-success"><i class="bi bi-check-circle-fill"></i> {!! session('success') !!}</div>
    @endif
    @if(session('error'))
        <div class="m-alert m-alert-danger"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@stack('scripts')
</body>
</html>
