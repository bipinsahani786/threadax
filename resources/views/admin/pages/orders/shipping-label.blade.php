<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label — {{ $order->order_number }}</title>
    <style>
        @page {
            size: 4in 6in;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px 10px;
            background-color: #0F172A;
            color: #000000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
        }
        .actions-bar {
            width: 380px;
            margin-bottom: 14px;
            display: flex;
            justify-content: space-between;
            gap: 8px;
        }
        .btn {
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        .btn-print {
            background-color: #FFFFFF;
            color: #000000;
            border: none;
            box-shadow: 0 4px 12px rgba(255,255,255,0.2);
        }
        .btn-print:hover {
            background-color: #F1F5F9;
            transform: translateY(-1px);
        }
        .btn-close {
            background-color: rgba(255,255,255,0.1);
            color: #FFFFFF;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-close:hover {
            background-color: rgba(255,255,255,0.2);
        }

        /* ── Label Container (4in x 6in Aspect Ratio) ── */
        .label-container {
            width: 380px;
            background: #FFFFFF;
            border: 2px solid #000000;
            padding: 10px 12px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            font-size: 11px;
            line-height: 1.3;
        }

        /* Header */
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000000;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }
        .brand-name {
            font-size: 18px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .brand-sub {
            font-size: 8px;
            font-weight: 700;
            color: #555;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .courier-chip {
            background: #000000;
            color: #FFFFFF;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 4px;
            text-align: right;
        }

        /* Routing Box */
        .routing-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border: 1.5px solid #000000;
            background: #FAFAFA;
            margin-bottom: 6px;
        }
        .routing-col {
            padding: 5px 8px;
        }
        .routing-col:first-child {
            border-right: 1.5px solid #000000;
        }
        .field-label {
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #444444;
            margin-bottom: 1px;
        }
        .city-text {
            font-size: 15px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .pincode-text {
            font-size: 16px;
            font-weight: 900;
            font-family: monospace;
            letter-spacing: 1px;
        }

        /* Barcode Section */
        .barcode-card {
            border-bottom: 1.5px dashed #000000;
            padding-bottom: 6px;
            margin-bottom: 6px;
            text-align: center;
        }
        .barcode-svg-wrapper {
            margin: 2px auto;
            max-width: 280px;
        }
        .barcode-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            font-family: monospace;
            font-weight: 800;
            color: #111;
            margin-top: 2px;
            padding: 0 4px;
        }

        /* Ship To Address Box */
        .ship-to-card {
            border-bottom: 1.5px solid #000000;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }
        .customer-name {
            font-size: 13px;
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .customer-address {
            font-size: 10.5px;
            line-height: 1.35;
            color: #111;
        }
        .customer-phone {
            font-size: 12px;
            font-weight: 900;
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Payment Banner */
        .payment-card {
            border: 2px solid #000000;
            padding: 6px 8px;
            text-align: center;
            margin-bottom: 6px;
        }
        .payment-card.cod {
            background-color: #000000;
            color: #FFFFFF;
        }
        .payment-card.prepaid {
            background-color: #FAFAFA;
            color: #000000;
        }
        .payment-head {
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .payment-amount {
            font-size: 13px;
            font-weight: 900;
            margin-top: 1px;
        }

        /* Itemized Content */
        .contents-card {
            border-bottom: 1px solid #000000;
            padding-bottom: 5px;
            margin-bottom: 5px;
            font-size: 9.5px;
        }
        .contents-table {
            width: 100%;
            border-collapse: collapse;
        }
        .contents-table th {
            text-align: left;
            border-bottom: 1px solid #DDD;
            padding: 1px 0;
            font-size: 8.5px;
            text-transform: uppercase;
            color: #555;
        }
        .contents-table td {
            padding: 2px 0;
        }

        /* Return / Sender Address */
        .return-card {
            font-size: 8.5px;
            line-height: 1.25;
            color: #333333;
        }
        .return-title {
            font-weight: 800;
            text-transform: uppercase;
            font-size: 8px;
            color: #555;
            margin-bottom: 1px;
        }

        @media print {
            body {
                background: none !important;
                padding: 0 !important;
                margin: 0 !important;
                justify-content: flex-start !important;
            }
            .actions-bar {
                display: none !important;
            }
            .label-container {
                box-shadow: none !important;
                border: 2px solid #000000 !important;
                width: 100% !important;
                max-width: 4in !important;
                padding: 8px 10px !important;
                page-break-inside: avoid !important;
            }
        }
    </style>
</head>
<body>

    {{-- Top Action Toolbar (Hidden during Print) --}}
    <div class="actions-bar">
        <button onclick="window.print()" class="btn btn-print">
            <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-3.11 2.215-5.679 5.28-5.679 3.065 0 5.52 2.569 5.28 5.679m-10.56 0A5.98 5.98 0 0012 18a5.98 5.98 0 005.28-4.171m-10.56 0a5.99 5.99 0 0110.56 0M9 10.5h6M9 7.5h6"/></svg>
            <span>Print Label (4x6 Thermal)</span>
        </button>
        <button onclick="window.close()" class="btn btn-close">
            ✕ Close
        </button>
    </div>

    @php
        $sysSettings = \App\Models\Setting::all()->pluck('value', 'key');
        $brandName = $sysSettings['brand_name'] ?? 'THREADAX';
        $brandTagline = $sysSettings['brand_tagline'] ?? 'PREMIUM STREETWEAR LOGISTICS';
        $companyName = $sysSettings['company_name'] ?? 'THREADAX LOGISTICS WAREHOUSE';
        $storeAddress = $sysSettings['store_address'] ?? 'ThreadAx HQ, Mumbai, Maharashtra 400001';
        $contactPhone = $sysSettings['whatsapp_number'] ?? ($sysSettings['contact_phone'] ?? '919876543210');
        $contactEmail = $sysSettings['contact_email'] ?? 'support@threadax.co.in';
    @endphp

    {{-- Main Shipping Label Frame --}}
    <div class="label-container">
        
        {{-- 1. Header --}}
        <div class="header-row">
            <div>
                <div class="brand-name">{{ $brandName }}</div>
                <div class="brand-sub">{{ $brandTagline }}</div>
            </div>
            <div class="courier-chip">
                {{ $order->effective_courier ?? 'EXPRESS LOGISTICS' }}
            </div>
        </div>

        {{-- 2. Routing Box --}}
        <div class="routing-grid">
            <div class="routing-col">
                <div class="field-label">Destination City</div>
                <div class="city-text">{{ $order->address->city ?? 'INDIA' }}</div>
            </div>
            <div class="routing-col" style="text-align: right;">
                <div class="field-label">Destination Pincode</div>
                <div class="pincode-text">{{ $order->address->pincode ?? '000000' }}</div>
            </div>
        </div>

        {{-- 3. Vector SVG Barcode --}}
        <div class="barcode-card">
            @php
                $barcodeText = $order->effective_awb ?: $order->order_number;
                $svgBarcode = \App\Services\BarcodeService::generateSvg($barcodeText, 270, 42);
            @endphp
            <div class="barcode-svg-wrapper">
                {!! $svgBarcode !!}
            </div>
            <div class="barcode-meta">
                <span>AWB: {{ $barcodeText }}</span>
                <span>ORDER: #{{ $order->order_number }}</span>
            </div>
        </div>

        {{-- 4. Ship To Customer Address --}}
        <div class="ship-to-card">
            <div class="field-label">Ship To (Delivery Address):</div>
            <div class="customer-name">{{ $order->address->name ?? ($order->user->name ?? 'Valued Customer') }}</div>
            <div class="customer-address">
                {{ $order->address->line1 ?? '' }}<br>
                @if($order->address?->line2) {{ $order->address->line2 }}<br> @endif
                @if($order->address?->landmark) Landmark: {{ $order->address->landmark }}<br> @endif
                <strong>{{ $order->address->city ?? '' }}, {{ $order->address->state ?? '' }} — {{ $order->address->pincode ?? '' }}</strong>
            </div>
            <div class="customer-phone">
                <span>📞 Phone:</span>
                <span>{{ $order->address->phone ?? ($order->user->phone ?? 'N/A') }}</span>
                @if($order->address?->alternate_phone)
                    <span style="color:#555;font-weight:normal;">/ {{ $order->address->alternate_phone }}</span>
                @endif
            </div>
        </div>

        {{-- 5. Payment Details Banner --}}
        <div class="payment-card {{ $order->payment_method === 'cod' ? 'cod' : 'prepaid' }}">
            @if($order->payment_method === 'cod')
                <div class="payment-head">CASH ON DELIVERY (COD)</div>
                <div class="payment-amount">
                    COLLECT CASH: ₹{{ number_format($order->total, 2) }}
                </div>
            @else
                <div class="payment-head">PREPAID ORDER</div>
                <div class="payment-amount" style="font-size:11px;font-weight:700;">
                    DO NOT COLLECT CASH (Paid Online: ₹{{ number_format($order->total, 2) }})
                </div>
            @endif
        </div>

        {{-- 6. Items in Package --}}
        <div class="contents-card">
            <div class="field-label">Package Contents ({{ $order->items->sum('quantity') }} items • Weight: 0.45 KG):</div>
            <table class="contents-table">
                <thead>
                    <tr>
                        <th style="width: 58%;">Item</th>
                        <th style="width: 27%;">Variant</th>
                        <th style="width: 15%; text-align: right;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        @php
                            $prod = $item->variant?->product;
                            $pName = $prod?->name ?? 'Streetwear Drop';
                        @endphp
                        <tr>
                            <td style="font-weight: 700;">{{ Str::limit($pName, 32) }}</td>
                            <td>
                                {{ $item->variant?->size ?? '-' }} / {{ $item->variant?->color ?? '-' }}
                            </td>
                            <td style="text-align: right; font-weight: 800;">{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 7. Return / Dispatch Address --}}
        <div class="return-card">
            <div class="return-title">If Undelivered, Return To:</div>
            <strong>{{ $companyName }}</strong><br>
            {{ $storeAddress }}<br>
            Support: +{{ $contactPhone }} | Email: {{ $contactEmail }}
        </div>

    </div>

</body>
</html>
