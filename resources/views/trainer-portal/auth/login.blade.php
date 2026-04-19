<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack – Trainer Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #1a2744; --accent: #00b894; }
        body { font-family:'Inter',sans-serif; min-height:100vh; margin:0; background:linear-gradient(135deg,#1a2744 0%,#0d1f3c 100%); display:flex; align-items:center; justify-content:center; padding:20px; }
        .box { background:#fff; border-radius:20px; padding:44px 40px; max-width:400px; width:100%; box-shadow:0 24px 80px rgba(0,0,0,.3); }
        .logo { width:48px; height:48px; background:var(--accent); border-radius:12px; display:flex; align-items:center; justify-content:center; font-family:'Sora',sans-serif; font-size:1.2rem; color:#fff; font-weight:700; margin-bottom:20px; }
        h2 { font-family:'Sora',sans-serif; font-size:1.4rem; font-weight:700; margin-bottom:4px; }
        .sub { color:#64748b; font-size:.875rem; margin-bottom:30px; }
        label { font-size:.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#64748b; margin-bottom:5px; display:block; }
        input { width:100%; padding:11px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.9rem; outline:none; margin-bottom:18px; transition:border-color .15s; }
        input:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(0,184,148,.12); }
        button { width:100%; padding:12px; background:var(--accent); color:#fff; border:none; border-radius:9px; font-family:'Sora',sans-serif; font-weight:600; font-size:.95rem; cursor:pointer; transition:opacity .15s; }
        button:hover { opacity:.88; }
        .error { background:#fee2e2; color:#dc2626; border:1px solid #fecaca; border-radius:9px; padding:10px 14px; font-size:.85rem; margin-bottom:18px; }
        .links { text-align:center; margin-top:22px; font-size:.8rem; color:#94a3b8; }
        .links a { color:var(--primary); text-decoration:none; font-weight:500; }
    </style>
</head>
<body>
<div class="box">
    <div class="logo">T</div>
    <h2>Trainer Portal</h2>
    <p class="sub">Sign in to manage your members</p>

    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('trainer.login.post') }}">
        @csrf
        <label>Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="yourname@fittrack.co.ke" required autofocus>

        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required>

        <label style="display:flex;align-items:center;gap:8px;font-size:.83rem;color:#64748b;margin-bottom:20px;cursor:pointer;font-weight:400;text-transform:none;letter-spacing:0;">
            <input type="checkbox" name="remember" style="width:auto;margin-bottom:0;accent-color:var(--accent);">
            Keep me signed in
        </label>

        <button type="submit">Sign In →</button>
    </form>

    <div class="links">
        <a href="{{ route('member.login') }}">Member Portal</a>
        &nbsp;·&nbsp;
        <a href="{{ route('login') }}">Staff Portal</a>
    </div>
</div>
</body>
</html>
