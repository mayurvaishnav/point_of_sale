<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; text-align: center; }
        .receipt { max-width: 300px; margin: auto; padding: 10px; padding-top: 25px;}
        .bold { font-weight: bold; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 5px; }
        .total-row td { font-weight: bold; }
        .dashed-line { border-top: 1px dashed black; margin: 10px 0; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="center">
            <strong>Bowes Tyres and Auto Centre</strong><br>
            Timahoe Road, Portlaoise, Co. Laois<br>
            Phone: 057-8665075<br>
            VAT No: IE397032GH
        </div>

        <div class="dashed-line"></div>

        <table>
            <tr>
                <td class="text-left"><strong>Status:</strong> {{ $order->status }}</td>
            </tr>
            <tr>
                <td class="text-left"><strong>Receipt No:</strong> {{ $order->invoice_number }}</td>
            </tr>
            <tr>
                <td class="text-left"><strong>Date:</strong> {{ $order->created_at->format('d-m-Y h:i A') }}</td>
            </tr>
            <tr>
                <td class="text-left"><strong>Customer:</strong> 
                    @if ($order->customer)
                        {{ $order->customer->name }}
                    @else
                        Walk-in Customer
                    @endif
                </td>
            </tr>
        </table>

        <div class="dashed-line"></div>

        <table>
            <thead>
                <tr>
                    <th class="text-left">Item</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderDetails as $detail)
                    <tr>
                        <td class="text-left">{{ $detail->product_name }} x{{ $detail->quantity }}</td>
                        <td class="text-right">{{ config('app.currency_symbol') }}{{ $detail->total }}</td>
                    </tr>
                @endforeach
                <tr class="dashed-line"></tr>
                <tr class="total-row">
                    <td class="text-right">Subtotal:</td>
                    <td class="text-right">{{ config('app.currency_symbol') }}{{ $order->total_before_tax }}</td>
                </tr>
                <tr class="total-row">
                    <td class="text-right">Vat:</td>
                    <td class="text-right">{{ config('app.currency_symbol') }}{{ $order->tax }}</td>
                </tr>
                @if ($order->discount != 0)
                    <tr class="total-row">
                        <td class="text-right">Discount:</td>
                        <td class="text-right">-{{ config('app.currency_symbol') }}{{ $order->discount }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td class="text-right">Total:</td>
                    <td class="text-right">{{ config('app.currency_symbol') }}{{ $order->total_after_discount }}</td>
                </tr>
            </tbody>
        </table>

        <div class="dashed-line"></div>

        <p>Thank you for your purchase!</p>
        <div class="dashed-line"></div>
        <h3><strong>Terms & Conditions</strong></h3>
        <p>No refund without a valid receipt</p>
        <p>Please retain this receipt as proof of purchase</p>
    </div>
</body>
</html>
