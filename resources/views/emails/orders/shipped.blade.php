<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your ThreadAX Order Has Shipped!</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #0F172A;
            color: #1E293B;
            margin: 0;
            padding: 20px 10px;
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
            padding: 30px 24px;
            text-align: center;
            color: #FFFFFF;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            letter-spacing: 3px;
            font-weight: 900;
            text-transform: uppercase;
        }
        .header p {
            margin: 6px 0 0;
            color: #94A3B8;
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .hero {
            background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);
            padding: 25px 24px;
            text-align: center;
            color: #FFFFFF;
        }
        .hero h2 {
            margin: 0 0 8px;
            font-size: 22px;
            font-weight: 800;
        }
        .hero p {
            margin: 0;
            font-size: 14px;
            opacity: 0.95;
        }
        .content {
            padding: 28px 24px;
        }
        .tracking-card {
            background-color: #F8FAFC;
            border: 2px dashed #93C5FD;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            text-align: center;
        }
        .tracking-label {
            font-size: 11px;
            font-weight: 800;
            color: #3B82F6;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .tracking-awb {
            font-family: monospace;
            font-size: 20px;
            font-weight: 900;
            color: #0F172A;
            letter-spacing: 2px;
            margin-bottom: 6px;
        }
        .tracking-courier {
            font-size: 13px;
            color: #64748B;
            font-weight: 600;
        }
        .btn-cta {
            display: block;
            width: 100%;
            box-sizing: border-box;
            background-color: #000000;
            color: #FFFFFF !important;
            text-decoration: none;
            font-weight: 800;
            font-size: 14px;
            text-align: center;
            padding: 14px 20px;
            border-radius: 10px;
            margin-top: 24px;
            letter-spacing: 0.5px;
        }
        .footer {
            background-color: #0F172A;
            padding: 20px 24px;
            text-align: center;
            color: #64748B;
            font-size: 11px;
            line-height: 1.6;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>THREADAX</h1>
        <p>Premium Luxury Streetwear</p>
    </div>

    <div class="hero">
        <h2>🚚 Your Package Is On The Way!</h2>
        <p>Dispatched from our warehouse and in transit to your doorstep.</p>
    </div>

    <div class="content">
        <p style="font-size: 15px; color: #1E293B; margin-top: 0;">
            Hey <strong>{{ $order->shipping_name ?? $order->user?->name ?? 'Customer' }}</strong>,
        </p>
        <p style="font-size: 13px; color: #64748B; line-height: 1.6;">
            Great news! Your ThreadAX order <strong>#{{ $order->order_number }}</strong> has been packed and handed over to our delivery partner.
        </p>

        <div class="tracking-card">
            <div class="tracking-label">Courier & AWB Tracking Code</div>
            <div class="tracking-awb">{{ $order->effective_awb ?: 'TX-EXP-' . $order->order_number }}</div>
            <div class="tracking-courier">Carried by <strong>{{ $order->effective_courier ?: 'ThreadAX Express Courier' }}</strong></div>
        </div>

        <div style="background-color: #F8FAFC; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
            <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748B; margin-bottom: 4px;">Delivery Address</div>
            <div style="font-size: 13px; font-weight: 600; color: #0F172A;">
                {{ $order->shipping_address ?? ($order->address?->line1 . ', ' . $order->address?->city . ' - ' . $order->address?->pincode) }}
            </div>
        </div>

        <a href="{{ $order->tracking_url ?: route('frontend.tracking', ['order_number' => $order->order_number]) }}" class="btn-cta">
            TRACK LIVE SHIPMENT ➔
        </a>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} ThreadAX Inc. All rights reserved.<br>
        Questions? Contact us at support@threadax.co.in</p>
    </div>
</div>

</body>
</html>
