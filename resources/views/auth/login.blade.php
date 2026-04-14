<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack – Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root { --ft-accent: #e94560; --ft-primary: #1a1a2e; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: var(--ft-primary);
            overflow: hidden;
        }
        /* Left panel – brand */
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }
        .login-left::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            border: 80px solid rgba(233,69,96,.12);
            top: -120px; right: -120px;
        }
        .login-left::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            border: 50px solid rgba(233,69,96,.07);
            bottom: -60px; left: -60px;
        }
        .logo-box {
            width: 56px; height: 56px;
            background: var(--ft-accent);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif;
            font-size: 1.6rem; color: #fff; font-weight: 700;
            margin-bottom: 28px;
        }
        .brand-headline {
            font-family: 'Sora', sans-serif;
            font-size: 2.6rem;
            color: #fff;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 16px;
        }
        .brand-sub {
            color: rgba(255,255,255,.5);
            font-size: .95rem;
            max-width: 340px;
            line-height: 1.6;
        }
        .feature-list {
            margin-top: 40px;
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            color: rgba(255,255,255,.6);
            font-size: .85rem;
            padding: 6px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .feature-list li i { color: var(--ft-accent); }

        /* Right panel – form */
        .login-right {
            width: 440px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 48px;
        }
        .login-title {
            font-family: 'Sora', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .login-sub { color: #6c757d; font-size: .875rem; margin-bottom: 36px; }
        .ft-label {
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #6c757d;
            margin-bottom: 5px;
            display: block;
        }
        .ft-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e3e8f0;
            border-radius: 9px;
            font-size: .9rem;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            margin-bottom: 20px;
        }
        .ft-input:focus {
            border-color: var(--ft-accent);
            box-shadow: 0 0 0 3px rgba(233,69,96,.1);
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: var(--ft-accent);
            color: #fff;
            border: none;
            border-radius: 9px;
            font-size: .95rem;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            cursor: pointer;
            transition: opacity .15s;
            margin-top: 8px;
        }
        .btn-login:hover { opacity: .88; }
        .error-msg {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: .85rem;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-right { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="login-left">
        <div class="logo-box">F</div>
        <h1 class="brand-headline">Manage your<br>gym smarter.</h1>
        <p class="brand-sub">FitTrack gives you complete control over members, payments, attendance, and growth — all from one dashboard.</p>
        <ul class="feature-list">
            <li><i class="bi bi-check-circle-fill"></i> Member & subscription tracking</li>
            <li><i class="bi bi-check-circle-fill"></i> M-Pesa & cash payment records</li>
            <li><i class="bi bi-check-circle-fill"></i> Daily attendance with validation</li>
            <li><i class="bi bi-check-circle-fill"></i> Revenue & growth reports</li>
            <li><i class="bi bi-check-circle-fill"></i> Works fully offline (XAMPP)</li>
        </ul>
    </div>

    <div class="login-right">
        <h2 class="login-title">Welcome back</h2>
        <p class="login-sub">Sign in to your FitTrack account</p>

        @if($errors->any())
            <div class="error-msg"><i class="bi bi-exclamation-triangle"></i> {{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <label class="ft-label" for="email">Email address</label>
            <input class="ft-input" id="email" name="email" type="email"
                   value="{{ old('email') }}" placeholder="admin@fittrack.co.ke" required autofocus>

            <label class="ft-label" for="password">Password</label>
            <input class="ft-input" id="password" name="password" type="password"
                   placeholder="••••••••" required>

            <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:#6c757d;margin-bottom:20px;cursor:pointer;">
                <input type="checkbox" name="remember" style="accent-color:var(--ft-accent);">
                Keep me signed in
            </label>

            <button type="submit" class="btn-login">Sign In <i class="bi bi-arrow-right"></i></button>
        </form>

        <p style="text-align:center;margin-top:28px;font-size:.78rem;color:#adb5bd;">
            FitTrack v1.0 &nbsp;·&nbsp; Built for Kenyan gyms
        </p>
    </div>
</body>
</html>
