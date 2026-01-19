<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background-color: #e9ecef; font-weight: bold; }
        .summary-box { border: 1px solid #ddd; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo.png') }}" alt="Logo" style="height: 60px; margin-bottom: 10px;">
        <h1>DISTROZONE</h1>
        <h2>Sales Report</h2>
        <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        <p>Generated: {{ $generatedAt }}</p>
    </div>

    <div class="summary-box">
        <strong>Summary:</strong><br>
        Online Sales: Rp {{ number_format($onlineTotal, 0, ',', '.') }} ({{ $onlineOrders->count() }} orders)<br>
        Offline Sales: Rp {{ number_format($offlineTotal, 0, ',', '.') }} ({{ $offlineTransactions->count() }} transactions)<br>
        <strong>Grand Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
    </div>

    <h3>Online Orders</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Order Code</th>
                <th>Customer</th>
                <th>Date</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($onlineOrders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $order->order_code }}</td>
                <td>{{ $order->buyer->name }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td class="text-right">Rp {{ number_format($order->total_payment, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL:</td>
                <td class="text-right">Rp {{ number_format($onlineTotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <h3 style="margin-top: 30px;">Offline Transactions (POS)</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Transaction Code</th>
                <th>Cashier</th>
                <th>Date</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($offlineTransactions as $index => $trx)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $trx->transaction_code }}</td>
                <td>{{ $trx->user->name }}</td>
                <td>{{ $trx->created_at->format('d M Y') }}</td>
                <td class="text-right">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL:</td>
                <td class="text-right">Rp {{ number_format($offlineTotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>