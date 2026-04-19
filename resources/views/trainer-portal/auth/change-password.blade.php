<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password – FitTrack Trainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Inter',sans-serif; min-height:100vh; background:linear-gradient(135deg,#1a2744,#0d1f3c); display:flex; align-items:center; justify-content:center; padding:20px; }
        .box { background:#fff; border-radius:18px; padding:40px; max-width:400px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,.25); }
        h2 { font-family:'Sora',sans-serif; font-size:1.3rem; font-weight:700; margin-bottom:6px; }
        .sub { color:#64748b; font-size:.875rem; margin-bottom:26px; }
        label { font-size:.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#64748b; margin-bottom:5px; display:block; }
        input[type=password] { width:100%; padding:11px 13px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:.9rem; outline:none; margin-bottom:16px; }
        input[type=password]:focus { border-color:#00b894; box-shadow:0 0 0 3px rgba(0,184,148,.12); }
        button { width:100%; padding:12px; background:#00b894; color:#fff; border:none; border-radius:9px; font-family:'Sora',sans-serif; font-weight:600; cursor:pointer; }
    </style>
</head>
<body>
<div class="box">
    <h2>Set Your Password</h2>
    <p class="sub">First login — please create a personal password to continue.</p>
    @if($errors->any())
        <div style="background:#fee2e2;color:#dc2626;border:1px solid #fecaca;border-radius:8px;padding:10px 13px;font-size:.83rem;margin-bottom:16px;">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('trainer.change-password.post') }}">
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
