<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your ThreadAX Order Is Confirmed!</title>
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
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            padding: 25px 24px;
            text-align: center;
            color: #FFFFFF;
            border-bottom: 2px solid #334155;
        }
        .hero h2 {
            margin: 0 0 8px;
            font-size: 22px;
            font-weight: 800;
            color: #F8FAFC;
        }
        .hero p {
            margin: 0;
            font-size: 14px;
            color: #94A3B8;
        }
        .content {
            padding: 28px 24px;
        }
        .badge-box {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
        }
        .badge-col {
            font-size: 12px;
        }
        .badge-label {
            color: #64748B;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .badge-val {
            color: #0F172A;
            font-weight: 800;
            font-size: 14px;
        }
        .item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #F1F5F9;
        }
        .item-info {
            font-size: 13px;
        }
        .item-name {
            font-weight: 700;
            color: #0F172A;
        }
        .item-meta {
            color: #64748B;
            font-size: 12px;
        }
        .item-price {
            font-weight: 800;
            font-size: 13px;
            color: #0F172A;
        }
        .totals-box {
            background-color: #F8FAFC;
            border-radius: 12px;
            padding: 16px;
            margin-top: 20px;
        }
        .total-line {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #64748B;
            margin-bottom: 6px;
        }
        .total-line.grand {
            border-top: 1px solid #E2E8F0;
            padding-top: 8px;
            margin-top: 8px;
            color: #0F172A;
            font-weight: 900;
            font-size: 16px;
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
        <h2>🛍️ Order Confirmed!</h2>
        <p>We've received your order and our warehouse team is preparing your drop.</p>
    </div>

    <div class="content">
        <p style="font-size: 15px; color: #1E293B; margin-top: 0;">
            Hey <strong>{{ $order->shipping_name ?? $order->user?->name ?? 'Streetwear Enthusiast' }}</strong>,
        </p>
        <p style="font-size: 13px; color: #64748B; line-height: 1.6;">
            Thank you for shopping with ThreadAX! Your order <strong>#{{ $order->order_number }}</strong> has been verified and confirmed. You will receive real-time tracking updates as soon as your parcel is dispatched.
        </p>

        <div class="badge-box">
            <div class="badge-col">
                <div class="badge-label">Order Number</div>
                <div class="badge-val">#{{ $order->order_number }}</div>
            </div>
            <div class="badge-col">
                <div class="badge-label">Payment Method</div>
                <div class="badge-val">{{ strtoupper($order->payment_method ?? 'ONLINE') }}</div>
            </div>
            <div class="badge-col">
                <div class="badge-label">Total Amount</div>
                <div class="badge-val">₹{{ number_format($order->total, 2) }}</div>
            </div>
        </div>

        <h4 style="margin: 20px 0 10px; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #0F172A;">Order Items</h4>
        
        @foreach($order->items as $item)
            <div class="item-row">
                <div class="item-info">
                    <div class="item-name">{{ $item->variant?->product?->name ?? 'Streetwear Item' }}</div>
                    <div class="item-meta">
                        Qty: {{ $item->quantity }}
                        @if($item->variant?->size) • Size: {{ $item->variant->size }} @endif
                        @if($item->variant?->color) • Color: {{ $item->variant->color }} @endif
                    </div>
                </div>
                <div class="item-price">₹{{ number_format($item->total, 2) }}</div>
            </div>
        @endforeach

        <div class="totals-box">
            <div class="total-line">
                <span>Subtotal</span>
                <span>₹{{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->discount > 0)
                <div class="total-line" style="color: #059669;">
                    <span>Discount ({{ $order->coupon_code }})</span>
                    <span>-₹{{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
            <div class="total-line">
                <span>Shipping</span>
                <span>{{ $order->shipping > 0 ? '₹' . number_format($order->shipping, 2) : 'FREE' }}</span>
            </div>
            <div class="total-line grand">
                <span>Grand Total</span>
                <span>₹{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <a href="{{ route('frontend.tracking', ['order_number' => $order->order_number]) }}" class="btn-cta">
            TRACK ORDER STATUS ➔
        </a>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} ThreadAX Inc. All rights reserved.<br>
        Questions? Contact us at support@threadax.co.in</p>
    </div>
</div>

</body>
</html>
