<!DOCTYPE html>
<html>
<head>
    <title>Invoice from Bowes Tyres and Auto Centre</title>
</head>
<body>
    <p>Dear {{ $order->customer->name ?? 'Customer' }},</p>

    <p>Thank you for your recent purchase at Bowes Tyres and Auto Centre.</p>

    <p>Please find attached your invoice for your order placed on {{ $order->created_at->format('d-m-Y h:i A') }}.</p>

    <p><strong>Invoice Number:</strong> {{ $order->invoice_number }}</p>
    <p><strong>Total Amount:</strong> {{ config('app.currency_symbol') }}{{ $order->total_after_discount }}</p>

    <p>If you have any questions regarding this invoice, feel free to contact us.</p>

    <p>Best regards,</p>
    <p><strong>Bowes Tyres and Auto Centre</strong></p>
    <p>Timahoe Road, Portlaoise, Co. Laois</p>
    <p>Phone: 057-8665075 | Email: info@bowestyres.com</p>
</body>
</html>
