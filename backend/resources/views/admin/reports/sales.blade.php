@extends('layouts.admin')

@section('page_title', 'Sales Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Sales</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <!-- Filter -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.reports.sales') }}" method="GET" class="form-inline">
                        <div class="form-group mr-2">
                            <label class="mr-2">Start Date:</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="form-group mr-2">
                            <label class="mr-2">End Date:</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-search"></i> Filter</button>
                        <a href="{{ route('admin.reports.export-pdf', ['type' => 'sales', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                           class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Online Sales</span>
                    <span class="info-box-number">Rp {{ number_format($onlineSales['total'], 0, ',', '.') }}</span>
                    <small>{{ $onlineSales['count'] }} orders</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-cash-register"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Offline Sales</span>
                    <span class="info-box-number">Rp {{ number_format($offlineSales['total'], 0, ',', '.') }}</span>
                    <small>{{ $offlineSales['count'] }} transactions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Sales</span>
                    <span class="info-box-number">Rp {{ number_format($totalSales, 0, ',', '.') }}</span>
                    <small>{{ $totalTransactions }} total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-percentage"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Avg per Transaction</span>
                    <span class="info-box-number">Rp {{ number_format($totalTransactions > 0 ? $totalSales / $totalTransactions : 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Breakdown Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daily Sales Breakdown</h3>
                </div>
                <div class="card-body">
                    <canvas id="dailySalesChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Tables -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Online Orders</h3>
                </div>
                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Order Code</th>
                                <th>Customer</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($onlineSales['orders'] as $order)
                                <tr>
                                    <td>{{ $order->order_code }}</td>
                                    <td>{{ $order->buyer->name }}</td>
                                    <td class="text-right">Rp {{ number_format($order->total_payment, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title">Offline Transactions</h3>
                </div>
                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Transaction Code</th>
                                <th>Cashier</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offlineSales['transactions'] as $trx)
                                <tr>
                                    <td>{{ $trx->transaction_code }}</td>
                                    <td>{{ $trx->user->name }}</td>
                                    <td class="text-right">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    var ctx = document.getElementById('dailySalesChart').getContext('2d');
    var data = @json($dailyBreakdown);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(item => item.date),
            datasets: [{
                label: 'Online Sales',
                data: data.map(item => item.online),
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.1
            }, {
                label: 'Offline Sales',
                data: data.map(item => item.offline),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush