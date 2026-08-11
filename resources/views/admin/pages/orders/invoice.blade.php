<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header table {
            width: 100%;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .info-section {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-section table {
            width: 100%;
        }
        .info-section td {
            vertical-align: top;
            width: 50%;
        }
        .info-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            text-align: left;
            border-bottom: 1px solid #ddd;
            padding: 10px;
            text-transform: uppercase;
            font-size: 12px;
            background-color: #f9f9f9;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .totals-table {
            width: 40%;
            float: right;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 5px 10px;
        }
        .totals-table .total-row {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #000;
            padding-top: 10px;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            clear: both;
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <h1>THREADAX</h1>
                    <p>Premium Streetwear</p>
                </td>
                <td class="text-right">
                    <h2>INVOICE</h2>
                    <p><strong>Order #:</strong> {{ $order->order_number }}</p>
                    <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td>
                    <div class="info-title">Billed To:</div>
                    <p>
                        <strong>{{ $order->shipping_name }}</strong><br>
                        {{ $order->shipping_street }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_pincode }}<br>
                        Phone: {{ $order->shipping_phone }}
                    </p>
                </td>
                <td>
                    <div class="info-title">Payment Details:</div>
                    <p>
                        <strong>Method:</strong> {{ strtoupper($order->payment_method) }}<br>
                        <strong>Status:</strong> {{ ucfirst($order->payment_status) }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->product_name }}</strong><br>
                    <span style="font-size: 12px; color: #666;">Size: {{ $item->size }} | Color: {{ $item->color }}</span>
                </td>
                <td>₹{{ number_format($item->price) }}</td>
                <td>{{ $item->quantity }}</td>
                <td class="text-right">₹{{ number_format($item->price * $item->quantity) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">₹{{ number_format($order->subtotal) }}</td>
        </tr>
        @if($order->discount > 0)
        <tr>
            <td>Discount:</td>
            <td class="text-right">-₹{{ number_format($order->discount) }}</td>
        </tr>
        @endif
        <tr class="total-row">
            <td>Total:</td>
            <td class="text-right">₹{{ number_format($order->total) }}</td>
        </tr>
    </table>

    <div class="footer">
        Thank you for shopping with ThreadAX! If you have any questions, please contact us at support@threadax.co.in
    </div>

</body>
</html>
