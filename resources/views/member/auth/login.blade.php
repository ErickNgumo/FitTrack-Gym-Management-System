<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack – Member Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #0f3460; --accent: #e94560; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh; margin: 0;
            background: linear-gradient(135deg, #0f3460 0%, #1a1a2e 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        .login-box {
            width: 100%; max-width: 420px;
            background: #fff; border-radius: 20px;
            padding: 44px 40px; box-shadow: 0 24px 80px rgba(0,0,0,.25);
        }
        .logo { width: 48px; height: 48px; background: var(--accent); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-family: 'Sora', sans-serif; font-size: 1.3rem; color: #fff; font-weight: 700; margin-bottom: 20px; }
        h2 { font-family: 'Sora', sans-serif; font-size: 1.4rem; font-weight: 700; margin-bottom: 4px; }
        .sub { color: #64748b; font-size: .875rem; margin-bottom: 30px; }
        .ft-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: #64748b; margin-bottom: 5px; display: block; }
        .ft-input { width: 100%; padding: 11px 13px; border: 1.5px solid #e2e8f0; border-radius: 9px; font-size: .9rem; outline: none; margin-bottom: 18px; transition: border-color .15s, box-shadow .15s; }
        .ft-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(15,52,96,.1); }
        .btn-login { width: 100%; padding: 12px; background: var(--accent); color: #fff; border: none; border-radius: 9px; font-family: 'Sora', sans-serif; font-size: .95rem; font-weight: 600; cursor: pointer; transition: opacity .15s; }
        .btn-login:hover { opacity: .88; }
        .error-box { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; border-radius: 9px; padding: 10px 14px; font-size: .85rem; margin-bottom: 18px; }
        .portal-links { text-align: center; margin-top: 24px; font-size: .8rem; color: #94a3b8; }
        .portal-links a { color: var(--primary); text-decoration: none; font-weight: 500; }
        .hint { background: #f0f4ff; border-radius: 8px; padding: 10px 14px; font-size: .78rem; color: #475569; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="login-box">
    <div class="logo">F</div>
    <h2>Member Portal</h2>
    <p class="sub">Sign in to track your progress</p>

    @if($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
    @endif

    <div class="hint">
        <strong>📱 Login with your phone number</strong> — the same number registered at the gym front desk.
    </div>

    <form method="POST" action="{{ route('member.login.post') }}">
        @csrf
        <label class="ft-label">Phone Number</label>
        <input class="ft-input" name="phone" type="tel" value="{{ old('phone') }}"
               placeholder="e.g. 07XXXXXXXX" required autofocus>

        <label class="ft-label">Password</label>
        <input class="ft-input" name="password" type="password" placeholder="••••••••" required>

        <label style="display:flex;align-items:center;gap:8px;font-size:.83rem;color:#64748b;margin-bottom:20px;cursor:pointer;">
            <input type="checkbox" name="remember" style="accent-color:var(--accent);">
            Keep me signed in
        </label>

        <button type="submit" class="btn-login">Sign In →</button>
    </form>

    <div class="portal-links">
        <a href="{{ route('trainer.login') }}">Trainer Portal</a>
        &nbsp;·&nbsp;
        <a href="{{ route('login') }}">Staff Portal</a>
    </div>
</div>
</body>
</html>
