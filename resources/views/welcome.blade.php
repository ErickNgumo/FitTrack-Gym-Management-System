<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack – Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:   #1a1a2e;
            --accent:    #e94560;
            --green:     #00b894;
            --blue:      #0984e3;
            --gold:      #fdcb6e;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: var(--primary);
            overflow-x: hidden;
        }

        /* ── Background decoration ── */
        .bg-deco {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            overflow: hidden;
        }
        .bg-deco::before {
            content: '';
            position: absolute;
            width: 700px; height: 700px;
            border-radius: 50%;
            border: 120px solid rgba(233,69,96,.06);
            top: -200px; right: -200px;
        }
        .bg-deco::after {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            border: 80px solid rgba(0,184,148,.05);
            bottom: -150px; left: -150px;
        }

        /* ── Header ── */
        header {
            position: relative; z-index: 1;
            padding: 36px 48px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo {
            display: flex; align-items: center; gap: 12px;
        }
        .logo-icon {
            width: 44px; height: 44px;
            background: var(--accent);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif;
            font-size: 1.3rem; font-weight: 800; color: #fff;
            box-shadow: 0 4px 18px rgba(233,69,96,.4);
        }
        .logo-name {
            font-family: 'Sora', sans-serif;
            font-size: 1.4rem; font-weight: 700; color: #fff;
            letter-spacing: -.3px;
        }
        .logo-name span { color: var(--accent); }
        .header-badge {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.1);
            color: rgba(255,255,255,.55);
            font-size: .72rem;
            padding: 5px 12px;
            border-radius: 20px;
            letter-spacing: .04em;
        }

        /* ── Hero copy ── */
        .hero {
            position: relative; z-index: 1;
            text-align: center;
            padding: 64px 24px 20px;
        }
        .hero-pill {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(233,69,96,.12);
            border: 1px solid rgba(233,69,96,.25);
            color: #ff7891;
            font-size: .75rem; font-weight: 600;
            padding: 5px 14px; border-radius: 20px;
            margin-bottom: 24px;
            letter-spacing: .04em;
        }
        .hero h1 {
            font-family: 'Sora', sans-serif;
            font-size: clamp(2.2rem, 5vw, 3.4rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.12;
            margin-bottom: 16px;
            letter-spacing: -.5px;
        }
        .hero h1 em {
            font-style: normal;
            background: linear-gradient(90deg, var(--accent), #ff8fa3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            color: rgba(255,255,255,.5);
            font-size: 1rem; line-height: 1.65;
            max-width: 480px; margin: 0 auto 48px;
        }

        /* ── Portal cards ── */
        .portals {
            position: relative; z-index: 1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            max-width: 960px;
            margin: 0 auto;
            padding: 0 24px 80px;
        }

        .portal-card {
            background: rgba(255,255,255,.04);
            border: 1.5px solid rgba(255,255,255,.08);
            border-radius: 20px;
            padding: 36px 30px 32px;
            text-decoration: none;
            display: flex; flex-direction: column;
            gap: 0;
            transition: transform .2s ease, border-color .2s ease, background .2s ease, box-shadow .2s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .portal-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--card-glow);
            opacity: 0;
            transition: opacity .2s;
        }
        .portal-card:hover {
            transform: translateY(-6px);
            border-color: var(--card-accent);
            background: rgba(255,255,255,.07);
            box-shadow: 0 20px 60px rgba(0,0,0,.3), 0 0 0 1px var(--card-accent) inset;
        }
        .portal-card:hover::before { opacity: 1; }

        /* Per-card accent colours */
        .card-admin   { --card-accent: rgba(233,69,96,.5);  --card-glow: radial-gradient(circle at top left, rgba(233,69,96,.06), transparent 60%); }
        .card-member  { --card-accent: rgba(9,132,227,.5);  --card-glow: radial-gradient(circle at top left, rgba(9,132,227,.06), transparent 60%); }
        .card-trainer { --card-accent: rgba(0,184,148,.5);  --card-glow: radial-gradient(circle at top left, rgba(0,184,148,.06), transparent 60%); }

        .card-icon {
            width: 54px; height: 54px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 22px;
            flex-shrink: 0;
        }
        .card-admin   .card-icon { background: rgba(233,69,96,.15); color: #ff7891; }
        .card-member  .card-icon { background: rgba(9,132,227,.15); color: #74b9ff; }
        .card-trainer .card-icon { background: rgba(0,184,148,.15); color: #55efc4; }

        .card-badge {
            display: inline-block;
            font-size: .65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
            padding: 3px 9px; border-radius: 20px;
            margin-bottom: 10px;
        }
        .card-admin   .card-badge { background: rgba(233,69,96,.15); color: #ff7891; }
        .card-member  .card-badge { background: rgba(9,132,227,.15); color: #74b9ff; }
        .card-trainer .card-badge { background: rgba(0,184,148,.15); color: #55efc4; }

        .card-title {
            font-family: 'Sora', sans-serif;
            font-size: 1.25rem; font-weight: 700; color: #fff;
            margin-bottom: 10px;
        }
        .card-desc {
            color: rgba(255,255,255,.45);
            font-size: .875rem; line-height: 1.6;
            margin-bottom: 28px;
            flex: 1;
        }

        .card-features {
            list-style: none;
            margin-bottom: 28px;
        }
        .card-features li {
            color: rgba(255,255,255,.4);
            font-size: .78rem;
            padding: 4px 0;
            display: flex; align-items: center; gap: 8px;
        }
        .card-features li i { font-size: .65rem; }
        .card-admin   .card-features li i { color: rgba(233,69,96,.7); }
        .card-member  .card-features li i { color: rgba(9,132,227,.7); }
        .card-trainer .card-features li i { color: rgba(0,184,148,.7); }

        .card-cta {
            display: flex; align-items: center; justify-content: space-between;
            padding: 13px 16px;
            border-radius: 11px;
            font-family: 'Sora', sans-serif;
            font-size: .875rem; font-weight: 600;
            transition: opacity .15s;
        }
        .card-admin   .card-cta { background: rgba(233,69,96,.2); color: #ff7891; }
        .card-member  .card-cta { background: rgba(9,132,227,.2); color: #74b9ff; }
        .card-trainer .card-cta { background: rgba(0,184,148,.2); color: #55efc4; }
        .portal-card:hover .card-cta { opacity: .85; }

        .card-login-hint {
            font-size: .7rem;
            color: rgba(255,255,255,.25);
            margin-top: 12px;
            text-align: center;
            font-family: 'Inter', sans-serif;
        }
        .card-login-hint code {
            background: rgba(255,255,255,.07);
            padding: 1px 5px;
            border-radius: 4px;
            font-size: .68rem;
            color: rgba(255,255,255,.35);
        }

        /* ── Footer ── */
        footer {
            position: relative; z-index: 1;
            text-align: center;
            padding: 0 24px 40px;
            color: rgba(255,255,255,.2);
            font-size: .75rem;
        }
        footer a { color: rgba(255,255,255,.3); text-decoration: none; }
        footer a:hover { color: rgba(255,255,255,.55); }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            header { padding: 24px 20px 0; }
            .header-badge { display: none; }
            .hero { padding: 48px 16px 16px; }
            .portals { padding: 0 16px 60px; gap: 16px; }
            .portal-card { padding: 28px 22px 24px; }
        }
    </style>
</head>
<body>

<div class="bg-deco"></div>

<!-- Header -->
<header>
    <div class="logo">
        <div class="logo-icon">F</div>
        <div class="logo-name">Fit<span>Track</span></div>
    </div>
    <div class="header-badge"><i class="bi bi-shield-check"></i> &nbsp;Secure Portals</div>
</header>

<!-- Hero -->
<div class="hero">
    <div class="hero-pill">
        <i class="bi bi-lightning-charge-fill"></i>
        Gym Management System
    </div>
    <h1>Welcome to <em>FitTrack</em></h1>
    <p>Choose your portal below to sign in. Each portal is tailored to your role at the gym.</p>
</div>

<!-- Portal Cards -->
<div class="portals">

    {{-- Admin / Staff --}}
    <a href="{{ route('login') }}" class="portal-card card-admin">
        <div class="card-icon"><i class="bi bi-shield-lock-fill"></i></div>
        <span class="card-badge"><i class="bi bi-circle-fill"></i> &nbsp;Admin & Staff</span>
        <div class="card-title">Staff Portal</div>
        <p class="card-desc">Full gym management — members, payments, subscriptions, attendance, trainers, and reports.</p>
        <ul class="card-features">
            <li><i class="bi bi-circle-fill"></i> Member registration & management</li>
            <li><i class="bi bi-circle-fill"></i> M-Pesa & cash payment records</li>
            <li><i class="bi bi-circle-fill"></i> Attendance tracking & reports</li>
            <li><i class="bi bi-circle-fill"></i> Revenue & growth analytics</li>
        </ul>
        <div class="card-cta">
            Sign in as Admin / Staff
            <i class="bi bi-arrow-right"></i>
        </div>
        <p class="card-login-hint">Login with: <code>email</code> + <code>password</code></p>
    </a>

    {{-- Member --}}
    <a href="{{ route('member.login') }}" class="portal-card card-member">
        <div class="card-icon"><i class="bi bi-person-fill"></i></div>
        <span class="card-badge"><i class="bi bi-circle-fill"></i> &nbsp;Members</span>
        <div class="card-title">Member Portal</div>
        <p class="card-desc">Check your membership status, view workout history, track attendance, and see payment receipts.</p>
        <ul class="card-features">
            <li><i class="bi bi-circle-fill"></i> Membership & subscription status</li>
            <li><i class="bi bi-circle-fill"></i> Workout logs from your trainer</li>
            <li><i class="bi bi-circle-fill"></i> Attendance history</li>
            <li><i class="bi bi-circle-fill"></i> Payment receipts</li>
        </ul>
        <div class="card-cta">
            Sign in as Member
            <i class="bi bi-arrow-right"></i>
        </div>
        <p class="card-login-hint">Login with: <code>phone number</code> + <code>password</code></p>
    </a>

    {{-- Trainer --}}
    <a href="{{ route('trainer.login') }}" class="portal-card card-trainer">
        <div class="card-icon"><i class="bi bi-trophy-fill"></i></div>
        <span class="card-badge"><i class="bi bi-circle-fill"></i> &nbsp;Trainers</span>
        <div class="card-title">Trainer Portal</div>
        <p class="card-desc">Manage your clients, log workout sessions, track progress, and add private coaching notes.</p>
        <ul class="card-features">
            <li><i class="bi bi-circle-fill"></i> View assigned members</li>
            <li><i class="bi bi-circle-fill"></i> Log & track workout sessions</li>
            <li><i class="bi bi-circle-fill"></i> Add private coaching notes</li>
            <li><i class="bi bi-circle-fill"></i> Monitor client progress</li>
        </ul>
        <div class="card-cta">
            Sign in as Trainer
            <i class="bi bi-arrow-right"></i>
        </div>
        <p class="card-login-hint">Login with: <code>email</code> + <code>password</code></p>
    </a>

</div>

<!-- Footer -->
<footer>
    FitTrack v1.1 &nbsp;·&nbsp; Built for Kenyan Gyms &nbsp;·&nbsp;
    <a href="{{ route('login') }}">Admin</a>
</footer>

</body>
</html>
