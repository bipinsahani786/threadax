<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mailSubject }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #0F172A;
            color: #1E293B;
            margin: 0;
            padding: 30px 15px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .header {
            background-color: #000000;
            padding: 28px 24px;
            text-align: center;
            color: #FFFFFF;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 3px;
            font-weight: 900;
            text-transform: uppercase;
        }
        .header p {
            margin: 6px 0 0;
            color: #94A3B8;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .content {
            padding: 32px 28px;
            font-size: 14px;
            line-height: 1.7;
            color: #334155;
        }
        .greeting {
            font-size: 16px;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 16px;
        }
        .message-body {
            background-color: #F8FAFC;
            border-left: 4px solid #0F172A;
            padding: 20px;
            border-radius: 0 12px 12px 0;
            margin: 20px 0;
            white-space: pre-line;
            font-size: 14px;
            color: #1E293B;
        }
        .cta-box {
            text-align: center;
            margin: 30px 0 10px;
        }
        .cta-btn {
            display: inline-block;
            background-color: #000000;
            color: #FFFFFF;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .footer {
            background-color: #F1F5F9;
            padding: 20px 24px;
            text-align: center;
            font-size: 12px;
            color: #64748B;
            border-top: 1px solid #E2E8F0;
        }
    </style>
</head>
<body>
    <div class="container">
        
@php
    $emailBrand = \App\Models\Setting::get('brand_name', 'ThreadAX');
    $emailTagline = \App\Models\Setting::get('brand_tagline', 'Designed for the Culture. Engineered for Comfort.');
    $emailContact = \App\Models\Setting::get('contact_email', 'support@threadax.co.in');
@endphp
        {{-- Brand Header --}}
        <div class="header">
            <h1>{{ strtoupper($emailBrand) }}</h1>
            <p>Official Customer Correspondence</p>
        </div>

        {{-- Main Message Content --}}
        <div class="content">
            <div class="greeting">
                Hello {{ $user->name }},
            </div>

            <p>We are reaching out to you from the {{ $emailBrand }} Customer Experience team regarding your account.</p>

            <div class="message-body">
                {{ $messageBody }}
            </div>

            <p>If you have any questions or need further assistance, simply reply to this email or reach out to our team at <a href="mailto:{{ $emailContact }}" style="color: #000000; font-weight: 700;">{{ $emailContact }}</a>.</p>

            <div class="cta-box">
                <a href="{{ url('/') }}" class="cta-btn">Visit {{ $emailBrand }} Store</a>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p style="margin: 0 0 4px; font-weight: 700; color: #334155;">{{ $emailBrand }} Streetwear</p>
            <p style="margin: 0;">{{ $emailTagline }}</p>
        </div>

    </div>
</body>
</html>
