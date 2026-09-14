@php
    $emailBrand = \App\Models\Setting::get('brand_name', 'ThreadAX');
    $emailTagline = \App\Models\Setting::get('brand_tagline', 'Wear the Statement');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP — {{ $emailBrand }}</title>
    <style>
        body { margin: 0; font-family: 'Inter', Arial, sans-serif; background: #f4f4f4; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #0A0A0A; border-radius: 16px; overflow: hidden; }
        .header { background: #0A0A0A; padding: 40px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .logo { font-size: 28px; font-weight: 900; color: #fff; letter-spacing: 0.2em; }
        .logo span { color: #E8C547; }
        .body { padding: 48px 40px; }
        .otp-box { background: rgba(232,197,71,0.08); border: 2px solid rgba(232,197,71,0.3); border-radius: 12px; text-align: center; padding: 32px; margin: 32px 0; }
        .otp-code { font-size: 48px; font-weight: 900; color: #E8C547; letter-spacing: 0.3em; }
        .otp-label { font-size: 12px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.15em; margin-top: 8px; }
        h2 { font-size: 24px; font-weight: 700; color: #fff; margin: 0 0 12px; }
        p { font-size: 14px; color: rgba(255,255,255,0.5); line-height: 1.7; margin: 0 0 16px; }
        .warning { font-size: 12px; color: rgba(255,255,255,0.25); background: rgba(255,255,255,0.04); border-radius: 8px; padding: 16px; border: 1px solid rgba(255,255,255,0.06); }
        .footer { padding: 24px 40px; text-align: center; border-top: 1px solid rgba(255,255,255,0.06); }
        .footer p { font-size: 11px; color: rgba(255,255,255,0.2); margin: 0; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="logo">{{ strtoupper($emailBrand) }}</div>
    </div>
    <div class="body">
        <h2>Your Login OTP</h2>
        <p>Use this one-time password to log in to your {{ $emailBrand }} account. It expires in 10 minutes.</p>

        <div class="otp-box">
            <div class="otp-code">{{ $otp }}</div>
            <div class="otp-label">One-Time Password · Valid for 10 minutes</div>
        </div>

        <p>Enter this code on the login screen to access your account. Do not share this code with anyone.</p>

        <div class="warning">
            ⚠️ If you didn't request this OTP, please ignore this email. Your account remains secure.
        </div>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} {{ $emailBrand }} · {{ $emailTagline }}</p>
        <p style="margin-top:4px;">This is an automated email. Please do not reply.</p>
    </div>
</div>
</body>
</html>
