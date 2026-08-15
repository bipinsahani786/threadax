<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your ThreadAX Order Has Been Delivered!</title>
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
            background: linear-gradient(135deg, #059669 0%, #10B981 100%);
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
        .order-meta {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
        }
        .meta-col {
            font-size: 13px;
        }
        .meta-label {
            color: #64748B;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .meta-val {
            font-weight: 800;
            color: #0F172A;
        }
        .items-list {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .items-list th {
            text-align: left;
            padding: 10px 0;
            border-bottom: 2px solid #E2E8F0;
            font-size: 11px;
            text-transform: uppercase;
            color: #64748B;
        }
        .items-list td {
            padding: 12px 0;
            border-bottom: 1px solid #F1F5F9;
            font-size: 13px;
        }
        .totals {
            margin-top: 15px;
            border-top: 2px solid #E2E8F0;
            padding-top: 15px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 6px;
            color: #475569;
        }
        .totals-row.grand {
            font-size: 18px;
            font-weight: 900;
            color: #0F172A;
            border-top: 1px solid #CBD5E1;
            padding-top: 10px;
            margin-top: 10px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 30px 0 10px;
        }
        .btn {
            display: inline-block;
            background-color: #000000;
            color: #FFFFFF !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer {
            background-color: #F8FAFC;
            border-top: 1px solid #E2E8F0;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #64748B;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>

    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>THREADAX</h1>
            <p>Official Delivery Confirmation</p>
        </div>

        {{-- Hero --}}
        <div class="hero">
            <h2>Package Successfully Delivered! 🎉</h2>
            <p>Your drip has arrived at your doorstep. We hope you love your new fit!</p>
        </div>

        {{-- Main Body --}}
        <div class="content">
            <p style="font-size: 15px; margin-top: 0;">Hey <strong>{{ $order->address->name ?? ($order->user->name ?? 'Valued Customer') }}</strong>,</p>
            <p style="font-size: 13px; color: #475569; line-height: 1.6;">
                Great news! Your package for Order <strong>#{{ $order->order_number }}</strong> has been officially delivered. Your official GST Tax Invoice has been attached with this email for your records.
            </p>

            <table style="width: 100%; margin: 20px 0; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px; background: #FAFAFA;">
                <tr>
                    <td style="font-size: 12px; color: #64748B;">Order Number:</td>
                    <td style="font-size: 12px; font-weight: 800; text-align: right; color: #0F172A;">#{{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px; color: #64748B;">Delivery Date:</td>
                    <td style="font-size: 12px; font-weight: 800; text-align: right; color: #0F172A;">{{ now()->format('M d, Y h:i A') }}</td>
                </tr>
                @if($order->effective_courier)
                <tr>
                    <td style="font-size: 12px; color: #64748B;">Delivered By:</td>
                    <td style="font-size: 12px; font-weight: 800; text-align: right; color: #0F172A;">{{ $order->effective_courier }} (AWB: {{ $order->effective_awb }})</td>
                </tr>
                @endif
                <tr>
                    <td style="font-size: 12px; color: #64748B;">Payment Mode:</td>
                    <td style="font-size: 12px; font-weight: 800; text-align: right; color: #0F172A;">{{ strtoupper($order->payment_method) }} ({{ ucfirst($order->payment_status) }})</td>
                </tr>
            </table>

            {{-- Items List --}}
            <h3 style="font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin: 24px 0 10px; color: #0F172A;">Ordered Items</h3>
            <table class="items-list">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        @php $product = $item->variant?->product; @endphp
                        <tr>
                            <td>
                                <strong>{{ $product?->name ?? 'Drop Item' }}</strong>
                                <br>
                                <span style="font-size: 11px; color: #64748B;">
                                    @if($item->variant?->size) Size: {{ $item->variant->size }} @endif
                                    @if($item->variant?->color) | Color: {{ $item->variant->color }} @endif
                                </span>
                            </td>
                            <td style="text-align: center; font-weight: 700;">{{ $item->quantity }}</td>
                            <td style="text-align: right; font-weight: 800; color: #0F172A;">₹{{ number_format($item->total ?? ($item->price * $item->quantity), 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="text-align: right; margin-top: 10px;">
                <p style="margin: 3px 0; font-size: 13px; color: #64748B;">Subtotal: <strong style="color: #0F172A;">₹{{ number_format($order->subtotal, 2) }}</strong></p>
                @if($order->discount > 0)
                <p style="margin: 3px 0; font-size: 13px; color: #059669;">Discount: <strong>-₹{{ number_format($order->discount, 2) }}</strong></p>
                @endif
                <p style="margin: 3px 0; font-size: 13px; color: #64748B;">Shipping: <strong style="color: #0F172A;">{{ $order->shipping == 0 ? 'FREE' : '₹'.number_format($order->shipping, 2) }}</strong></p>
                <p style="margin: 8px 0 0; font-size: 18px; font-weight: 900; color: #0F172A;">Grand Total: ₹{{ number_format($order->total, 2) }}</p>
            </div>

            <div class="btn-wrapper">
                <a href="{{ route('account.orders.show', $order->id) }}" class="btn">View Order In Account →</a>
            </div>

            <div style="background-color: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 12px; padding: 14px; margin-top: 25px; text-align: center;">
                <p style="margin: 0; font-size: 12px; color: #1E40AF; font-weight: 600;">
                    📄 <strong>Tax Invoice Included:</strong> An official PDF invoice has been attached to this email.
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p><strong>ThreadAX Streetwear</strong> • Engineered for the bold</p>
            <p>Have questions about your order? Reply directly to this email or reach us on WhatsApp at +91 98765 43210</p>
            <p style="font-size: 11px; margin-top: 10px; color: #94A3B8;">&copy; {{ date('Y') }} ThreadAX. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
