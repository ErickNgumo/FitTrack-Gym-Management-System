<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trainer Portal') — FitTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --t-primary: #1a2744;
            --t-accent:  #00b894;
            --t-accent2: #e94560;
            --t-bg:      #f4f7f4;
            --t-card:    #ffffff;
            --t-border:  #e0e8e0;
            --t-muted:   #64748b;
            --t-sidebar: 245px;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--t-bg); margin: 0; }
        h1,h2,h3,h4,h5 { font-family: 'Sora', sans-serif; }

        #t-sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--t-sidebar); background: var(--t-primary);
            z-index: 100; overflow-y: auto; display: flex; flex-direction: column;
        }
        .t-brand {
            padding: 22px 18px 16px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; gap: 10px;
        }
        .t-brand-logo {
            width: 38px; height: 38px; border-radius: 10px;
            background: var(--t-accent);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif; font-weight: 700; color: #fff; font-size: 1rem;
        }
        .t-brand-text { color: #fff; font-size: 1rem; font-weight: 600; line-height: 1.2; }
        .t-brand-sub  { color: rgba(255,255,255,.3); font-size: .6rem; text-transform: uppercase; letter-spacing: .08em; }

        .t-nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 18px; color: rgba(255,255,255,.6);
            text-decoration: none; font-size: .875rem;
            transition: background .15s, color .15s;
        }
        .t-nav-link:hover, .t-nav-link.active { background: rgba(255,255,255,.08); color: #fff; }
        .t-nav-link.active { border-left: 3px solid var(--t-accent); padding-left: 15px; }
        .t-nav-link i { width: 18px; text-align: center; font-size: .95rem; }
        .t-nav-section { padding: 14px 18px 4px; color: rgba(255,255,255,.25); font-size: .62rem; letter-spacing: .1em; text-transform: uppercase; }
        .t-sidebar-footer { margin-top: auto; padding: 18px; border-top: 1px solid rgba(255,255,255,.07); }

        #t-topbar {
            position: fixed; top: 0; left: var(--t-sidebar); right: 0; height: 58px;
            background: #fff; border-bottom: 1px solid var(--t-border);
            display: flex; align-items: center; padding: 0 22px; z-index: 99; gap: 12px;
        }
        .t-page-title { font-family: 'Sora', sans-serif; font-size: .95rem; font-weight: 600; flex: 1; margin: 0; }

        #t-main { margin-left: var(--t-sidebar); margin-top: 58px; padding: 26px 26px 48px; }

        .t-card { background: var(--t-card); border: 1px solid var(--t-border); border-radius: 14px; padding: 22px; box-shadow: 0 1px 4px rgba(26,39,68,.04); }
        .t-kpi { background: var(--t-card); border: 1px solid var(--t-border); border-radius: 14px; padding: 20px; display: flex; align-items: center; gap: 14px; }
        .t-kpi-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
        .t-kpi-val   { font-family: 'Sora', sans-serif; font-size: 1.55rem; font-weight: 700; line-height: 1; }
        .t-kpi-label { font-size: .75rem; color: var(--t-muted); margin-top: 3px; }

        .t-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .t-table thead th { background: var(--t-bg); border-bottom: 1px solid var(--t-border); padding: 9px 13px; font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--t-muted); }
        .t-table tbody tr { border-bottom: 1px solid var(--t-border); transition: background .12s; }
        .t-table tbody tr:hover { background: #f0faf5; }
        .t-table tbody td { padding: 10px 13px; vertical-align: middle; }

        .t-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: .7rem; font-weight: 600; }
        .t-badge-green  { background: #dcfce7; color: #15803d; }
        .t-badge-red    { background: #fee2e2; color: #dc2626; }
        .t-badge-yellow { background: #fef3c7; color: #92400e; }
        .t-badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .t-badge-gray   { background: #f1f5f9; color: #64748b; }

        .t-alert { padding: 11px 15px; border-radius: 10px; font-size: .875rem; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 9px; }
        .t-alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .t-alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .t-alert-danger  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

        .t-btn-primary { background: var(--t-accent); color: #fff; border: none; padding: 8px 16px; border-radius: 8px; font-size: .85rem; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: opacity .15s; }
        .t-btn-primary:hover { opacity: .88; color: #fff; }
        .t-btn-danger { background: var(--t-accent2); color: #fff; border: none; padding: 7px 14px; border-radius: 8px; font-size: .82rem; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: opacity .15s; }
        .t-btn-danger:hover { opacity: .88; color: #fff; }
        .t-btn-outline { background: transparent; border: 1px solid var(--t-border); color: #1a2744; padding: 8px 16px; border-radius: 8px; font-size: .85rem; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background .12s; }
        .t-btn-outline:hover { background: var(--t-bg); color: #1a2744; }

        .t-label { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--t-muted); margin-bottom: 5px; display: block; }
        .t-input { width: 100%; padding: 9px 12px; border: 1.5px solid var(--t-border); border-radius: 8px; font-size: .875rem; outline: none; transition: border-color .15s, box-shadow .15s; background: #fff; }
        .t-input:focus { border-color: var(--t-accent); box-shadow: 0 0 0 3px rgba(0,184,148,.12); }
    </style>
    @stack('styles')
</head>
<body>

<nav id="t-sidebar">
    <div class="t-brand">
        <div class="t-brand-logo">T</div>
        <div>
            <div class="t-brand-text">FitTrack</div>
            <div class="t-brand-sub">Trainer Portal</div>
        </div>
    </div>

    <div class="t-nav-section">Overview</div>
    <a href="{{ route('trainer.dashboard') }}" class="t-nav-link {{ request()->routeIs('trainer.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>

    <div class="t-nav-section">My Members</div>
    <a href="{{ route('trainer.members') }}"   class="t-nav-link {{ request()->routeIs('trainer.member*') ? 'active' : '' }}"><i class="bi bi-people"></i> My Members</a>

    <div class="t-sidebar-footer">
        @php $tc = Auth::guard('trainer')->user(); $tr = $tc?->trainer; @endphp
        @if($tr)
        <div style="color:rgba(255,255,255,.5);font-size:.78rem;margin-bottom:2px;">{{ $tr->name }}</div>
        <div style="color:rgba(255,255,255,.3);font-size:.7rem;margin-bottom:10px;">{{ $tr->speciality ?? 'Trainer' }}</div>
        @endif
        <form method="POST" action="{{ route('trainer.logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.35);font-size:.8rem;padding:0;cursor:pointer;">
                <i class="bi bi-box-arrow-left"></i> Sign Out
            </button>
        </form>
    </div>
</nav>

<header id="t-topbar">
    <h1 class="t-page-title">@yield('page-title', 'Dashboard')</h1>
    <div style="display:flex;align-items:center;gap:12px;">
        <span style="font-size:.78rem;color:var(--t-muted);">{{ now()->format('d M Y') }}</span>
        <div style="width:32px;height:32px;border-radius:50%;background:var(--t-accent);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;font-size:.8rem;">
            {{ strtoupper(substr($tr->name ?? 'T', 0, 1)) }}
        </div>
    </div>
</header>

<main id="t-main">
    @if(session('success'))
        <div class="t-alert t-alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="t-alert t-alert-danger"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>
    @endif
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
