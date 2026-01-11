<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Report</title>
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
        <h1>DISTROZONE</h1>
        <h2>Stock Report</h2>
        <p>Generated: {{ $generatedAt }}</p>
    </div>

    <div class="summary-box">
        <strong>Summary:</strong><br>
        Total Products: {{ $products->count() }}<br>
        Total Stock: {{ $totalStock }} pcs<br>
        <strong>Total Stock Value: Rp {{ number_format($totalValue, 0, ',', '.') }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Product Name</th>
                <th>Brand</th>
                <th>Type</th>
                <th class="text-center">Stock</th>
                <th class="text-right">Cost Price</th>
                <th class="text-right">Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['brand'] }}</td>
                <td>{{ $product['type'] }}</td>
                <td class="text-center">{{ $product['total_stock'] }}</td>
                <td class="text-right">Rp {{ number_format($product['cost_price'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($product['total_stock'] * $product['cost_price'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL:</td>
                <td class="text-center">{{ $totalStock }}</td>
                <td></td>
                <td class="text-right">Rp {{ number_format($totalValue, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>