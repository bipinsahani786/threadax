<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tax Invoice - {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 8mm 8mm 8mm 8mm;
            size: a4 portrait;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #0F172A;
            font-size: 9.5px;
            line-height: 1.25;
            margin: 0;
            padding: 0;
            background: #FFFFFF;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .outer-border {
            border: 1.5px solid #000000;
            width: 100%;
        }

        /* ── Header ── */
        .header-cell {
            padding: 10px 12px;
            border-bottom: 1.5px solid #000000;
            vertical-align: top;
        }
        .brand-title {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #000000;
            margin: 0;
        }
        .brand-sub {
            font-size: 7.5px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #475569;
            margin-top: 1px;
        }
        .seller-details {
            font-size: 8px;
            color: #334155;
            line-height: 1.3;
            margin-top: 3px;
        }
        .invoice-title-badge {
            background: #000000;
            color: #FFFFFF;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 3px 8px;
            display: inline-block;
            margin-bottom: 4px;
        }
        .meta-text {
            font-size: 8.5px;
            color: #1E293B;
            line-height: 1.35;
        }

        /* ── Addresses ── */
        .address-row td {
            width: 50%;
            padding: 6px 10px;
            border-bottom: 1px solid #000000;
            vertical-align: top;
            background: #F8FAFC;
        }
        .address-box-title {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            border-bottom: 1px solid #CBD5E1;
            padding-bottom: 2px;
            margin-bottom: 3px;
        }
        .address-content {
            font-size: 8.5px;
            line-height: 1.3;
        }

        /* ── Items Table ── */
        .items-head th {
            background: #000000;
            color: #FFFFFF;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 5px 6px;
            border-right: 1px solid #334155;
            text-align: left;
        }
        .items-head th:last-child {
            border-right: none;
        }
        .item-row td {
            padding: 5px 6px;
            border-bottom: 1px solid #CBD5E1;
            border-right: 1px solid #E2E8F0;
            font-size: 8.5px;
            vertical-align: top;
        }
        .item-row td:last-child {
            border-right: none;
        }
        .item-row:nth-child(even) td {
            background: #FAFAFA;
        }

        /* ── Summary & Totals ── */
        .summary-cell-left {
            width: 55%;
            padding: 6px 8px;
            border-right: 1px solid #000000;
            border-bottom: 1px solid #000000;
            vertical-align: top;
        }
        .summary-cell-right {
            width: 45%;
            padding: 4px 8px;
            border-bottom: 1px solid #000000;
            vertical-align: top;
        }
        .totals-table td {
            padding: 2px 4px;
            font-size: 8.5px;
        }
        .grand-total-row td {
            border-top: 1.5px solid #000000;
            border-bottom: 1.5px solid #000000;
            font-size: 11px;
            font-weight: bold;
            color: #000000;
            background: #F1F5F9;
            padding: 4px 4px;
        }

        /* ── Words & Footer ── */
        .words-cell {
            padding: 4px 8px;
            border-bottom: 1px solid #000000;
            font-size: 8px;
            background: #F8FAFC;
        }
        .terms-cell {
            padding: 6px 8px;
            font-size: 7.5px;
            color: #64748B;
            line-height: 1.25;
            vertical-align: top;
            width: 60%;
        }
        .sign-cell {
            padding: 6px 8px;
            font-size: 8px;
            text-align: right;
            vertical-align: top;
            width: 40%;
        }
        .stamp-box {
            display: inline-block;
            border: 1px dashed #000000;
            padding: 3px 8px;
            font-weight: bold;
            font-size: 7.5px;
            letter-spacing: 0.5px;
            margin: 4px 0 2px;
            background: #FAFAFA;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>

@php
    $sysSettings = \App\Models\Setting::all()->pluck('value', 'key');
    $brandName = $sysSettings['brand_name'] ?? 'THREADAX';
    $brandTagline = $sysSettings['brand_tagline'] ?? 'PREMIUM STREETWEAR & APPAREL';
    $companyName = $sysSettings['company_name'] ?? 'THREADAX APPARELS PRIVATE LIMITED';
    $storeAddress = $sysSettings['store_address'] ?? 'ThreadAx HQ, Mumbai, Maharashtra 400001';
    $gstin = $sysSettings['company_gstin'] ?? '27AAACT9988F1Z2';
    $pan = $sysSettings['company_pan'] ?? 'AAACT9988F';
    $companyState = $sysSettings['company_state'] ?? 'Maharashtra (27)';
    $contactEmail = $sysSettings['contact_email'] ?? 'support@threadax.co.in';
    $contactPhone = $sysSettings['contact_phone'] ?? ($sysSettings['whatsapp_number'] ?? '+91 98765 43210');
    $invoicePrefix = $sysSettings['invoice_prefix'] ?? 'INV-';
    $defaultHsn = $sysSettings['default_hsn_code'] ?? '610910';
    $signatoryTitle = $sysSettings['invoice_signatory_title'] ?? 'THREADAX APPARELS PVT. LTD.';
    $stampText = $sysSettings['invoice_stamp_text'] ?? 'DIGITALLY AUTHORIZED';
    $termsText = $sysSettings['invoice_terms'] ?? "1. This is a computer-generated tax invoice and requires no physical signature.\n2. Goods once sold are eligible for return / exchange within 7 days in unworn condition with original tags.\n3. All disputes are subject to Mumbai Jurisdiction only.";
@endphp

<table class="outer-border">

    {{-- 1. Header Row --}}
    <tr>
        <td class="header-cell" style="width: 58%;">
            <div class="brand-title">{{ $brandName }}</div>
            <div class="brand-sub">{{ $brandTagline }}</div>
            <div class="seller-details">
                <strong>Sold By:</strong> {{ $companyName }}<br>
                <strong>HQ:</strong> {{ $storeAddress }}<br>
                @if($gstin)<strong>GSTIN:</strong> {{ $gstin }} | @endif
                @if($pan)<strong>PAN:</strong> {{ $pan }}<br>@endif
                <strong>State:</strong> {{ $companyState }} • Support: {{ $contactPhone }} | {{ $contactEmail }}
            </div>
        </td>
        <td class="header-cell text-right" style="width: 42%;">
            <div class="invoice-title-badge">TAX INVOICE</div>
            <div class="meta-text">
                <strong>Invoice No:</strong> {{ $invoicePrefix }}{{ $order->order_number }}<br>
                <strong>Invoice Date:</strong> {{ $order->created_at->format('d M, Y') }}<br>
                <strong>Order ID:</strong> #{{ $order->order_number }}<br>
                <strong>Order Date:</strong> {{ $order->created_at->format('d M, Y h:i A') }}<br>
                <strong>Place of Supply:</strong> {{ $order->address->state ?? 'Maharashtra' }}<br>
                @if($order->effective_courier)
                    <strong>Courier:</strong> {{ $order->effective_courier }} @if($order->effective_awb) (AWB: {{ $order->effective_awb }}) @endif
                @endif
            </div>
        </td>
    </tr>

    {{-- 2. Customer Address Row --}}
    <tr class="address-row">
        <td style="border-right: 1px solid #000000;">
            <div class="address-box-title">Billed To (Customer Details):</div>
            <div class="address-content">
                <strong>{{ strtoupper($order->address->name ?? ($order->user->name ?? 'Valued Customer')) }}</strong><br>
                Phone: {{ $order->address->phone ?? ($order->user->phone ?? 'N/A') }}<br>
                Email: {{ $order->user->email ?? 'customer@threadax.co.in' }}<br>
                State: {{ $order->address->state ?? 'India' }}
            </div>
        </td>
        <td>
            <div class="address-box-title">Shipped & Delivered To:</div>
            <div class="address-content">
                <strong>{{ strtoupper($order->address->name ?? ($order->user->name ?? 'Valued Customer')) }}</strong><br>
                {{ $order->address->line1 ?? '' }}<br>
                @if($order->address?->line2) {{ $order->address->line2 }}<br> @endif
                {{ $order->address->city ?? '' }}, {{ $order->address->state ?? '' }} — {{ $order->address->pincode ?? '' }}<br>
                Phone: {{ $order->address->phone ?? 'N/A' }}
            </div>
        </td>
    </tr>

    {{-- 3. Items Table Header & Body --}}
    <tr>
        <td colspan="2" style="padding: 0;">
            <table style="border-collapse: collapse; width: 100%;">
                <tr class="items-head">
                    <th style="width: 5%;" class="text-center">#</th>
                    <th style="width: 47%;">Description of Goods</th>
                    <th style="width: 12%;" class="text-center">HSN Code</th>
                    <th style="width: 8%;" class="text-center">Qty</th>
                    <th style="width: 13%;" class="text-right">Unit Price</th>
                    <th style="width: 15%;" class="text-right">Total</th>
                </tr>
                @foreach($order->items as $idx => $item)
                    @php
                        $prod = $item->variant?->product;
                        $pName = $prod?->name ?? 'Premium Streetwear Item';
                        $sku = $item->variant?->sku ?? ($prod?->sku ?? 'TX-' . $item->id);
                        $price = (float) $item->price;
                        $lineTotal = (float) ($item->total ?? ($price * $item->quantity));
                    @endphp
                    <tr class="item-row">
                        <td class="text-center font-bold">{{ $idx + 1 }}</td>
                        <td>
                            <strong>{{ $pName }}</strong><br>
                            <span style="font-size: 7.5px; color: #475569;">
                                SKU: {{ $sku }}
                                @if($item->variant?->size) | Size: <strong>{{ $item->variant->size }}</strong> @endif
                                @if($item->variant?->color) | Color: <strong>{{ $item->variant->color }}</strong> @endif
                            </span>
                        </td>
                        <td class="text-center" style="font-size: 8px;">{{ $defaultHsn }}</td>
                        <td class="text-center font-bold">{{ $item->quantity }}</td>
                        <td class="text-right">Rs. {{ number_format($price, 2) }}</td>
                        <td class="text-right font-bold">Rs. {{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>

    {{-- 4. Payment & Totals Summary Row --}}
    <tr>
        <td class="summary-cell-left">
            <div style="font-size: 8px; font-weight: bold; text-transform: uppercase; color: #475569; margin-bottom: 3px;">
                Payment & Settlement Details:
            </div>
            <table style="font-size: 8px;">
                <tr>
                    <td style="padding: 1px 0; color: #475569; width: 45%;">Payment Mode:</td>
                    <td style="padding: 1px 0; font-weight: bold; text-transform: uppercase;">
                        {{ $order->payment_method === 'cod' ? 'Cash on Delivery (COD)' : 'Online (Razorpay)' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 1px 0; color: #475569;">Payment Status:</td>
                    <td style="padding: 1px 0; font-weight: bold;">
                        @if($order->payment_status === 'paid')
                            [ PAID ONLINE ]
                        @else
                            [ CASH ON DELIVERY (PENDING) ]
                        @endif
                    </td>
                </tr>
                @if($order->payment && $order->payment->gateway_payment_id)
                <tr>
                    <td style="padding: 1px 0; color: #475569;">Gateway Txn ID:</td>
                    <td style="padding: 1px 0; font-family: monospace; font-weight: bold;">
                        {{ $order->payment->gateway_payment_id }}
                    </td>
                </tr>
                @endif
            </table>
        </td>
        <td class="summary-cell-right">
            @php
                $subtotal = (float) $order->subtotal;
                $discount = (float) $order->discount;
                $shipping = (float) $order->shipping;
                $grandTotal = (float) $order->total;

                $taxableValue = round($grandTotal / 1.05, 2);
                $gstTotal = round($grandTotal - $taxableValue, 2);
            @endphp
            <table class="totals-table">
                <tr>
                    <td style="color: #475569;">Item Subtotal:</td>
                    <td class="text-right font-bold">Rs. {{ number_format($subtotal, 2) }}</td>
                </tr>
                @if($discount > 0)
                <tr>
                    <td style="color: #059669; font-weight: bold;">Coupon Discount:</td>
                    <td class="text-right font-bold" style="color: #059669;">-Rs. {{ number_format($discount, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td style="color: #475569;">Shipping Charges:</td>
                    <td class="text-right font-bold">
                        {{ $shipping == 0 ? 'FREE' : 'Rs. ' . number_format($shipping, 2) }}
                    </td>
                </tr>
                <tr>
                    <td style="color: #64748B; font-size: 7.5px;">GST Included (5%):</td>
                    <td class="text-right" style="color: #64748B; font-size: 7.5px;">Rs. {{ number_format($gstTotal, 2) }}</td>
                </tr>
                <tr class="grand-total-row">
                    <td>Grand Total:</td>
                    <td class="text-right">Rs. {{ number_format($grandTotal, 2) }}</td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- 5. Amount in Words Row --}}
    <tr>
        <td colspan="2" class="words-cell">
            <strong>Amount in Words:</strong> 
            @php
                function numToWordsInvoiceDynamic($number) {
                    $hyphen      = '-';
                    $conjunction = ' and ';
                    $separator   = ', ';
                    $negative    = 'negative ';
                    $decimal     = ' point ';
                    $dictionary  = [
                        0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
                        5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
                        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
                        14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
                        18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
                        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
                        80 => 'Eighty', 90 => 'Ninety', 100 => 'Hundred', 1000 => 'Thousand',
                        100000 => 'Lakh', 10000000 => 'Crore'
                    ];

                    if (!is_numeric($number)) return '';
                    $number = (int) $number;
                    if ($number < 0) return $negative . numToWordsInvoiceDynamic(abs($number));

                    switch (true) {
                        case $number < 21:
                            return $dictionary[$number];
                        case $number < 100:
                            $tens = ((int) ($number / 10)) * 10;
                            $units = $number % 10;
                            return $dictionary[$tens] . ($units ? $hyphen . $dictionary[$units] : '');
                        case $number < 1000:
                            $hundreds = (int) ($number / 100);
                            $remainder = $number % 100;
                            return $dictionary[$hundreds] . ' ' . $dictionary[100] . ($remainder ? $conjunction . numToWordsInvoiceDynamic($remainder) : '');
                        case $number < 100000:
                            $thousands = (int) ($number / 1000);
                            $remainder = $number % 1000;
                            return numToWordsInvoiceDynamic($thousands) . ' ' . $dictionary[1000] . ($remainder ? $separator . numToWordsInvoiceDynamic($remainder) : '');
                        case $number < 10000000:
                            $lakhs = (int) ($number / 100000);
                            $remainder = $number % 100000;
                            return numToWordsInvoiceDynamic($lakhs) . ' ' . $dictionary[100000] . ($remainder ? $separator . numToWordsInvoiceDynamic($remainder) : '');
                        default:
                            $crores = (int) ($number / 10000000);
                            $remainder = $number % 10000000;
                            return numToWordsInvoiceDynamic($crores) . ' ' . $dictionary[10000000] . ($remainder ? $separator . numToWordsInvoiceDynamic($remainder) : '');
                    }
                }
            @endphp
            INR {{ numToWordsInvoiceDynamic($grandTotal) }} Only
        </td>
    </tr>

    {{-- 6. Terms & Signature Footer Row --}}
    <tr>
        <td class="terms-cell">
            <strong>Terms & Conditions:</strong><br>
            {!! nl2br(e($termsText)) !!}
        </td>
        <td class="sign-cell">
            <strong>For {{ $signatoryTitle }}</strong><br>
            <div class="stamp-box">
                &#10003; {{ $stampText }}
            </div><br>
            <span style="font-size: 7.5px; color: #64748B;">Authorized Signatory</span>
        </td>
    </tr>

</table>

</body>
</html>
