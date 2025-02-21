<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice { width: 100%; max-width: 800px; margin: 0 auto; padding: 20px; text-align: left; }
        .row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .column { width: 48%; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: right; }
        th { background: #f4f4f4; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; }
        .no-border { border: none !important;}
        .small-font { font-size: 12px; }
    </style>
</head>
<body>
    <div class="">
        <table>
            <tr class="no-border">
                <td class="text-left no-border">
                    <strong>Bowes Tyres and Auto Centre</strong><br>
                    Timahoe Road, Portlaoise, Co. Laois<br>
                    Phone: 057-8665075<br>
                    Email: info@bowestyres.com<br>
                    Vat No: IE397032GH
                </td>
                <td class="text-right no-border">
                    <h4 style="margin: 0;">Invoice No: {{ $order->invoice_number }}</h4>
                    <p style="margin: 0;">{{ $order->created_at->format('d-m-Y h:i A') }}</p>
                    <p style="">Status: <strong>{{ $order->status }}</strong></p>
                </td>
            </tr>
            <tr>
                <td class="text-left no-border">
                    <p>To:</p>
                    @if ($order->customer)
                        <strong>{{ $order->customer->name }}</strong><br>
                        {!! nl2br(e($order->customer->address)) !!}<br>
                        Phone: {{ $order->customer->phone }}<br>
                        Email: {{ $order->customer->email }}<br>
                        Car: {{ $order->customer->brand }} - {{ $order->customer->registration_no }}
                    @else
                        <strong>Walk-in Customer</strong><br>
                    @endif
                </td>
                <td class="text-right no-border">
                    @if ($customerAccountBalance != 0)
                    <p><h3>Customer Account Balance: @if ($customerAccountBalance < 0) - @endif {{ config( 'app.currency_symbol') }}{{ abs($customerAccountBalance) }}</h3></p>
                @endif
                </td>
            </tr>
        </table>
    
        <br>

        <table class="small-font">
            <thead>
                <tr>
                    <th class="text-left">Product</th>
                    <th>Unit Price</th>
                    <th>Qty</th>
                    <th>Vat(inc)</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderDetails as $detail)
                    <tr>
                        <td class="text-left">{{ $detail->product_name }}</td>
                        <td>{{ config( 'app.currency_symbol') }}{{ $detail->unit_price }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ config( 'app.currency_symbol') }}{{ $detail->tax }}</td>
                        <td>{{ config( 'app.currency_symbol') }}{{ $detail->total }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" class="no-border"></td>
                    <td class="text-right">Subtotal:</td>
                    <td>{{ config( 'app.currency_symbol') }}{{ $order->total_before_tax }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" class="no-border"></td>
                    <td class="text-right">Vat:</td>
                    <td>{{ config( 'app.currency_symbol') }}{{ $order->tax }}</td>
                </tr>
                @if ($order->discount != 0)
                    <tr class="total-row">
                        <td colspan="3" class="no-border"></td>
                        <td class="text-right">Discount:</td>
                        <td>{{ config( 'app.currency_symbol') }}{{ $order->discount }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td colspan="3" class="no-border"></td>
                    <td class="text-right">Total:</td>
                    <td>{{ config( 'app.currency_symbol') }}{{ $order->total_after_discount }}</td>
                </tr>
            </tbody>
        </table>
        <br>

        <div>
            <h3>Terms and Conditions</h3>
            <p>No refunds without a valid receipt!<br>
               Please retain this receipt as proof of purchase.<br>
               Thank you for your business!</p>
        </div>
    </div>
</body>
</html>
