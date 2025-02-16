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
                    <h2 style="margin: 0;">Account Statement</h2>
                    <p style="margin: 0;">{{ now()->format('d-m-Y h:i A') }}</p>
                </td>
            </tr>
            <tr>
                <td class="text-left no-border">
                    <p>To:</p>
                    <strong>{{ $customerAccount->customer->name }}</strong><br>
                    {!! nl2br(e($customerAccount->customer->address)) !!}<br>
                    Phone: {{ $customerAccount->customer->phone }}<br>
                    Email: {{ $customerAccount->customer->email }}
                </td>
            </tr>
        </table>
    
        <br>

        <table class="small-font">
            <thead>
                <tr>
                    <th class="text-left">Date</th>
                    <th class="text-left">Description</th>
                    <th>Amount Due</th>
                    <th>Amount Paid</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customerAccount->transactions as $transaction)
                    <tr>
                        <td class="text-left">{{ $transaction->created_at->format('Y-m-d') }}</td>
                        <td class="text-left">{{ $transaction->note }}</td>
                        <td>
                            @if($transaction->credit_amount)
                                {{ config('app.currency_symbol') }} 
                            @endif 
                            {{ $transaction->credit_amount }}</td>
                        <td>
                            @if($transaction->paid_amount)
                                {{ config('app.currency_symbol') }} 
                            @endif
                            {{ $transaction->paid_amount }}
                        </td>
                        <td>{{ config( 'app.currency_symbol') }} {{ $transaction->balance }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" class="no-border"></td>
                    <td class="text-right"><strong>Balance:</strong></td>
                    <td><strong>{{ config( 'app.currency_symbol') }} {{ $customerAccount->transactions->last()->balance ?? 0 }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
