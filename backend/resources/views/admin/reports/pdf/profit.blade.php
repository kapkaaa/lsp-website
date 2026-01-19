{{-- resources/views/admin/reports/pdf/profit.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profit Report</title>
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
        <h2>Profit Report</h2>
        <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        <p>Generated: {{ $generatedAt }}</p>
    </div>

    <div class="summary-box">
        <strong>Summary:</strong><br>
        Total Revenue: Rp {{ number_format($totalRevenue, 0, ',', '.') }}<br>
        Total COGS: Rp {{ number_format($totalCost, 0, ',', '.') }}<br>
        <strong>Net Profit: Rp {{ number_format($totalProfit, 0, ',', '.') }}</strong><br>
        Profit Margin: {{ number_format($profitMargin, 2) }}%
    </div>

    <h3>Profit Breakdown by Channel</h3>
    <table>
        <thead>
            <tr>
                <th>Channel</th>
                <th class="text-right">Revenue</th>
                <th class="text-right">COGS</th>
                <th class="text-right">Gross Profit</th>
                <th class="text-right">Margin</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Online Sales</td>
                <td class="text-right">Rp {{ number_format($onlineRevenue, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($onlineCost, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($onlineProfit, 0, ',', '.') }}</td>
                <td class="text-right">{{ $onlineRevenue > 0 ? number_format(($onlineProfit / $onlineRevenue) * 100, 2) : 0 }}%</td>
            </tr>
            <tr>
                <td>Offline Sales (POS)</td>
                <td class="text-right">Rp {{ number_format($offlineRevenue, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($offlineCost, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($offlineProfit, 0, ',', '.') }}</td>
                <td class="text-right">{{ $offlineRevenue > 0 ? number_format(($offlineProfit / $offlineRevenue) * 100, 2) : 0 }}%</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL</td>
                <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalCost, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($profitMargin, 2) }}%</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 10px; color: #666;">
        <strong>Notes:</strong><br>
        - Revenue: Total sales from completed transactions<br>
        - COGS: Cost of Goods Sold (product cost price)<br>
        - Gross Profit: Revenue - COGS<br>
        - Profit Margin: (Gross Profit / Revenue) × 100%
    </div>
</body>
</html>