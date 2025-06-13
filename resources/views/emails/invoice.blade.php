<!DOCTYPE html>
<html>
<head>
    <title>Invoice from Bowes Auto Centre Ltd</title>
</head>
<body>
    <p>Dear {{ $order->customer->name ?? 'Customer' }},</p>

    <p>Thank you for your recent purchase at Bowes Auto Centre Ltd.</p>

    <p>Please find attached your invoice for your order placed on {{ $order->created_at->format('d-m-Y h:i A') }}.</p>

    <p><strong>Invoice Number:</strong> {{ $order->invoice_number }}</p>
    <p><strong>Total Amount:</strong> {{ config('app.currency_symbol') }}{{ $order->total_after_discount }}</p>

    <p>If you have any questions regarding this invoice, feel free to contact us.</p>

    <p>Best regards,<br>
    <strong>Bowes Auto Centre Ltd</strong><br>
    Timahoe Road, Portlaoise, Co. Laois<br>
    Phone: 057-8665075 | Email: info@bowesautocentre.ie</p>
</body>
</html>
