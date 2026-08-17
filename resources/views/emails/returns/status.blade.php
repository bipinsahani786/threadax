<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreadAX Return & Exchange Update</title>
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
        .hero {
            background: #0F172A;
            padding: 24px;
            text-align: center;
            color: #FFFFFF;
            border-bottom: 3px solid #334155;
        }
        .hero h2 {
            margin: 0 0 6px;
            font-size: 20px;
            font-weight: 800;
        }
        .hero p {
            margin: 0;
            font-size: 13px;
            color: #CBD5E1;
        }
        .content {
            padding: 28px 24px;
        }
        .meta-card {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .meta-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            padding: 6px 0;
            border-bottom: 1px dashed #E2E8F0;
        }
        .meta-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .meta-label {
            color: #64748B;
            font-weight: 600;
        }
        .meta-value {
            color: #0F172A;
            font-weight: 700;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .item-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #F1F5F9;
        }
        .btn {
            display: block;
            text-align: center;
            background-color: #000000;
            color: #FFFFFF !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
            padding: 14px 20px;
            border-radius: 12px;
            margin: 24px 0 10px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .footer {
            background-color: #F8FAFC;
            padding: 20px 24px;
            text-align: center;
            border-top: 1px solid #E2E8F0;
            font-size: 11px;
            color: #94A3B8;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>THREADAX</h1>
            <p>Streetwear Re-defined • Return & Exchange</p>
        </div>

        {{-- Hero --}}
        @php
            $typeLabel = $returnRequest->isExchange() ? 'Exchange' : 'Return';
        @endphp
        <div class="hero">
            <h2>{{ $typeLabel }} Update: #{{ $returnRequest->return_number }}</h2>
            <p>For Order #{{ $order->order_number }}</p>
        </div>

        {{-- Content --}}
        <div class="content">
            <p style="font-size: 14px; line-height: 1.6; color: #334155; margin-top: 0;">
                Hello <strong>{{ $order->address->name ?? $returnRequest->user->name ?? 'Customer' }}</strong>,
            </p>

            @if($returnRequest->status === 'requested')
                <p style="font-size: 13px; line-height: 1.6; color: #475569;">
                    We have successfully received your <strong>{{ strtolower($typeLabel) }} request</strong>. Our customer support team will inspect the request within 24 hours.
                </p>
            @elseif($returnRequest->status === 'approved')
                <p style="font-size: 13px; line-height: 1.6; color: #475569;">
                    Great news! Your <strong>{{ strtolower($typeLabel) }} request has been approved</strong>. A reverse courier pickup will be initiated for your address shortly.
                </p>
            @elseif($returnRequest->status === 'pickup_scheduled')
                <p style="font-size: 13px; line-height: 1.6; color: #475569;">
                    Your reverse pickup has been scheduled with <strong>{{ $returnRequest->pickup_courier ?? 'our courier partner' }}</strong> (AWB: <strong>{{ $returnRequest->pickup_awb ?? 'Assigned' }}</strong>). Please ensure the item is kept ready with all original tags attached.
                </p>
            @elseif($returnRequest->status === 'refunded')
                <p style="font-size: 13px; line-height: 1.6; color: #059669;">
                    🎉 <strong>Refund Completed!</strong> We have released ₹{{ number_format($returnRequest->refund_amount, 2) }} back to your account.
                </p>
            @elseif($returnRequest->status === 'exchanged')
                <p style="font-size: 13px; line-height: 1.6; color: #059669;">
                    🔄 <strong>Exchange Dispatched!</strong> Your replacement drop has been packed and dispatched to your delivery address.
                </p>
            @elseif($returnRequest->status === 'rejected')
                <p style="font-size: 13px; line-height: 1.6; color: #E11D48;">
                    Your {{ strtolower($typeLabel) }} request could not be processed at this time.
                    @if($returnRequest->rejection_reason)
                        <br><strong>Reason:</strong> {{ $returnRequest->rejection_reason }}
                    @endif
                </p>
            @endif

            {{-- Summary Card --}}
            <div class="meta-card">
                <div class="meta-row">
                    <span class="meta-label">Request Type</span>
                    <span class="meta-value">{{ ucfirst($returnRequest->type) }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Status</span>
                    <span class="meta-value">{{ ucfirst(str_replace('_', ' ', $returnRequest->status)) }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Reason</span>
                    <span class="meta-value">{{ $returnRequest->reason_label }}</span>
                </div>
                @if($returnRequest->refund_amount > 0)
                    <div class="meta-row">
                        <span class="meta-label">Refund Value</span>
                        <span class="meta-value" style="color: #059669;">₹{{ number_format($returnRequest->refund_amount, 2) }}</span>
                    </div>
                @endif
                @if($returnRequest->pickup_courier)
                    <div class="meta-row">
                        <span class="meta-label">Pickup Courier</span>
                        <span class="meta-value">{{ $returnRequest->pickup_courier }} @if($returnRequest->pickup_awb) ({{ $returnRequest->pickup_awb }}) @endif</span>
                    </div>
                @endif
            </div>

            {{-- Items List --}}
            <h4 style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #0F172A; margin: 16px 0 8px;">
                Items in this Request
            </h4>
            @foreach($returnRequest->items as $item)
                <div class="item-row">
                    <div style="flex: 1;">
                        <p style="margin: 0; font-size: 13px; font-weight: 700; color: #0F172A;">
                            {{ $item->product->name ?? 'Streetwear Drop' }}
                        </p>
                        <p style="margin: 2px 0 0; font-size: 11px; color: #64748B;">
                            Qty: {{ $item->quantity }} • Size: {{ $item->originalVariant?->size ?? 'N/A' }}
                            @if($item->exchangeVariant)
                                ➔ <strong style="color: #2563EB;">New Size: {{ $item->exchangeVariant->size }}</strong>
                            @endif
                        </p>
                    </div>
                    <span style="font-size: 13px; font-weight: 700; color: #0F172A;">
                        ₹{{ number_format($item->total, 2) }}
                    </span>
                </div>
            @endforeach

            <a href="{{ route('account.returns.show', $returnRequest->id) }}" class="btn">
                View Live Return Tracker ↗
            </a>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p style="margin: 0 0 6px;">Questions? Contact us at support@threadax.in or WhatsApp us.</p>
            <p style="margin: 0;">© {{ date('Y') }} ThreadAX Streetwear India. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
