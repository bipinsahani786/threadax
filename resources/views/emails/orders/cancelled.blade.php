<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your ThreadAX Order Has Been Cancelled</title>
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
        .hero {
            background: linear-gradient(135deg, #991B1B 0%, #DC2626 100%);
            padding: 25px 24px;
            text-align: center;
            color: #FFFFFF;
        }
        .hero h2 {
            margin: 0 0 8px;
            font-size: 22px;
            font-weight: 800;
        }
        .content {
            padding: 28px 24px;
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
        <h2>⚠️ Order Cancellation Notice</h2>
        <p>Order #{{ $order->order_number }} has been cancelled.</p>
    </div>

    <div class="content">
        <p style="font-size: 15px; color: #1E293B; margin-top: 0;">
            Hey <strong>{{ $order->shipping_name ?? $order->user?->name ?? 'Customer' }}</strong>,
        </p>
        <p style="font-size: 13px; color: #64748B; line-height: 1.6;">
            Your order <strong>#{{ $order->order_number }}</strong> has been cancelled.
        </p>

        @if($order->payment_status === 'refunded' || ($order->payment && $order->payment->refund_id))
            <div style="background-color: #F5F3FF; border: 1px solid #DDD6FE; border-radius: 12px; padding: 18px; margin: 20px 0; text-align: left;">
                <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #6D28D9; letter-spacing: 1px; margin-bottom: 6px;">
                    ⚡ Auto-Refund Initiated
                </div>
                <div style="font-size: 18px; font-weight: 900; color: #4C1D95; margin-bottom: 8px;">
                    ₹{{ number_format($order->total, 2) }}
                </div>
                <div style="font-size: 12px; color: #5B21B6; line-height: 1.5;">
                    @if($order->payment?->refund_id)
                        <strong>Razorpay Refund ID:</strong> <span style="font-family: monospace;">{{ $order->payment->refund_id }}</span><br>
                    @endif
                    Amount will reflect in your source account (UPI / Bank / Card) within <strong>3–5 business days</strong>.
                </div>
            </div>
        @elseif($order->payment_status === 'paid')
            <div style="background-color: #F8FAFC; border-radius: 12px; padding: 16px; margin: 20px 0; font-size: 13px; color: #334155;">
                A full refund of <strong>₹{{ number_format($order->total, 2) }}</strong> has been scheduled and will reflect in your source account within 3–5 banking business days.
            </div>
        @else
            <p style="font-size: 13px; color: #64748B;">No payment was charged for this order (Cash on Delivery).</p>
        @endif

        <a href="{{ route('frontend.products.index') }}" class="btn-cta">
            EXPLORE NEW RELEASES ➔
        </a>
    </div>

    @php
        $emailSupport = \App\Models\Setting::get('contact_email', 'support@threadax.co.in');
        $emailPhone = \App\Models\Setting::get('contact_phone', '+91 98765 43210');
    @endphp
    <div class="footer">
        <p>© {{ date('Y') }} ThreadAX Inc. All rights reserved.<br>
        Questions? Contact us at <a href="mailto:{{ $emailSupport }}" style="color: #64748B; font-weight: 700;">{{ $emailSupport }}</a> or call <a href="tel:{{ $emailPhone }}" style="color: #64748B; font-weight: 700;">{{ $emailPhone }}</a></p>
    </div>
</div>

</body>
</html>
