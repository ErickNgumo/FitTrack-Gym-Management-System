<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Your Password – FitTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Inter',sans-serif; background:linear-gradient(135deg,#0f3460,#1a1a2e); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px; }
        .box { background:#fff; border-radius:18px; padding:40px; max-width:400px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,.2); }
        h2 { font-family:'Sora',sans-serif; font-size:1.3rem; font-weight:700; margin-bottom:6px; }
        .sub { color:#64748b; font-size:.875rem; margin-bottom:28px; }
        label { font-size:.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#64748b; margin-bottom:5px; display:block; }
        input[type=password] { width:100%; padding:11px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.9rem; outline:none; margin-bottom:16px; }
        input[type=password]:focus { border-color:#0f3460; box-shadow:0 0 0 3px rgba(15,52,96,.1); }
        button { width:100%; padding:12px; background:#e94560; color:#fff; border:none; border-radius:9px; font-family:'Sora',sans-serif; font-weight:600; font-size:.95rem; cursor:pointer; }
        .note { background:#fef3c7; border-radius:8px; padding:10px 13px; font-size:.8rem; color:#92400e; margin-bottom:22px; }
    </style>
</head>
<body>
<div class="box">
    <h2>Set Your Password</h2>
    <p class="sub">This is your first login. Please set a personal password before continuing.</p>
    <div class="note">⚠️ Choose something you'll remember — you'll need it every time you log in.</div>
    @if($errors->any())
        <div style="background:#fee2e2;color:#dc2626;border:1px solid #fecaca;border-radius:8px;padding:10px 13px;font-size:.83rem;margin-bottom:16px;">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('member.change-password.post') }}">
        @csrf
        <label>New Password</label>
        <input type="password" name="password" required minlength="8" placeholder="At least 8 characters">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" required placeholder="Repeat password">
        <button type="submit">Set Password & Continue</button>
    </form>
</div>
</body>
</html>
